<?php

namespace App\Http\Controllers\Admin;


use App\Models\ContactUs;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Carbon\Carbon;
use Prologue\Alerts\Facades\Alert;

/**
 * Class ContactUsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ContactUsCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\ContactUs');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/contactus');
        $this->crud->setEntityNameStrings(__('crud.contact_message'), __('crud.contact_messages'));
    }

    protected function setupListOperation()
    {
        $this->crud->addFilter([
            'name' => 'read_at',
            'type' => 'simple',
            'label' => __('crud.unread_only'),
        ], false, function ($value) {
            $this->crud->addClause('whereNull', 'read_at');
        });
        $this->initColumn();
    }

    protected function setupShowOperation()
    {
        $this->initColumn();
        if ($this->crud->getCurrentEntry()->read_at == null) {
            $this->crud->addButtonFromModelFunction('line', 'readMessage', 'readMessageButton');
        }
    }

    private function initColumn()
    {
        $this->crud->addColumn([
            'name' => 'name',
            'type' => 'text',
            'label' => __('crud.name'),
        ]);
        $this->crud->addColumn([
            'name' => 'message',
            'type' => 'text',
            'label' => __('crud.message'),
        ]);
        $this->crud->addColumn([
            'name' => 'email',
            'type' => 'email',
            'label' => __('crud.email'),
        ]);
        $this->crud->addColumn([
            'name' => 'phone',
            'type' => 'text',
            'label' => __('crud.phone'),
        ]);
        $this->crud->addColumn([
            'name' => 'read_at',
            'label' => __('crud.status'),
            'type' => 'custom_html',
            'value' => function ($entry) {
                if ($entry->read_at) {
                    return '<span class="status-badge status-active">' . __('crud.read') . '</span>';
                }
                return '<span class="status-badge status-inactive">' . __('crud.unread') . '</span>';
            },
        ]);
    }

    public function readMessage($id)
    {
        $message = ContactUs::query()->find($id);
        $message->read_at = Carbon::now();
        $message->save();
        Alert::success(__('crud.message_marked_read'))->flash();
        return redirect($this->crud->route);
    }

}
