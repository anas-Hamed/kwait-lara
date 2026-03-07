<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Constants;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Prologue\Alerts\Facades\Alert;

/**
 * Class AdminCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AdminCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/admin');
        CRUD::setEntityNameStrings(__('crud.admin'), __('crud.admins'));
        $this->crud->addClause('where','is_admin', true);
    }

    protected function setupListOperation()
    {
        $this->initColumns();

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'active',
            'label'=> __('crud.inactive_only'),
        ],
            false,
            function() {
                $this->crud->addClause('where','is_active',false);
            } );
    }

    public function setupShowOperation()
    {
        $this->initColumns();
        CRUD::column('verified_at')->type('date')->label(__('crud.verified_at'));
    }

    private function initColumns()
    {
        CRUD::column('name')->label(__('crud.name'));
        CRUD::column('email')->label(__('crud.email'));
        CRUD::column('phone')->label(__('crud.phone'));

        $this->crud->addButtonFromView('line','toggleActive','toggleActive');
    }

    public function toggleActive($id)
    {
        $user = User::query()->findOrFail($id);

        $user->is_active = !$user->is_active;
        $user->save();
        Alert::success(__('crud.operation_success'))->flash();
        return redirect()->back();
    }
}
