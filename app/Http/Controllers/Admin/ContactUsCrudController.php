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
        $this->crud->setEntityNameStrings('رسالة المستخدم', 'رسائل المستخدمين');
    }

    protected function setupListOperation()
    {
        $this->crud->addFilter([
            'name' => 'read_at',
            'type' => 'simple',
            'label' => 'الغير مقروءة فقط'
        ],false,
        function ($value){
            $this->crud->addClause('whereNull','read_at');
        }
        );
        $this->initColumn();
    }
    protected function setupShowOperation()
    {
        $this->initColumn();
        if ($this->crud->getCurrentEntry()->read_at == null){
            $this->crud->addButtonFromModelFunction('line','readMessage','readMessageButton');
        }
    }

    private function initColumn()
    {
        $this->crud->addColumn([
            "name" => "name",
            "type" => "text",
            "label" => "الاسم"
        ]);
        $this->crud->addColumn([
            "name" => "message",
            "type" => "text",
            "label" => "الرسالة"
        ]);
        $this->crud->addColumn([
            "name" => "email",
            "type" => "email",
            "label" => "البريد الإلكتروني"
        ]);
        $this->crud->addColumn([
            "name" => "phone",
            "type" => "text",
            "label" => "الهاتف"
        ]);
        $this->crud->addColumn([
            "name" => "read_at",
            "type" => "datetime",
            "label" => "تاريخ القراءة"
        ]);

    }
    public function readMessage( $id){
        $message = ContactUs::query()->find($id);
        $message->read_at = Carbon::now();
        $message->save();
        Alert::success('تم تعيين الرسالة كمقروءة')->flash();
        return redirect($this->crud->route);
    }

}
