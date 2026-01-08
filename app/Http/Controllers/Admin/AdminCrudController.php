<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Constants;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Prologue\Alerts\Facades\Alert;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AdminCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/admin');
        CRUD::setEntityNameStrings('مدير', 'المدراء');
        $this->crud->addClause('where','is_admin', true);
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->initColumns();

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'active',
            'label'=> 'غير الفعال فقط'
        ],
            false,
            function() {
                $this->crud->addClause('where','is_active',false);
            } );
    }

    public function setupShowOperation()
    {
        $this->initColumns();
        CRUD::column('verified_at')->type('date')->label('تم التفعيل في');
    }

    private function initColumns()
    {
        CRUD::column('name')->label('الاسم');
        CRUD::column('email')->label('البريد الالكتروني');
        CRUD::column('phone')->label('الهاتف');


        $this->crud->addButtonFromView('line','toggleActive','toggleActive');
    }


    public function toggleActive($id)
    {
        $user = User::query()->findOrFail($id);

        $user->is_active = !$user->is_active;
        $user->save();
        Alert::success('تمت العملية بنجاح')->flash();
        return redirect()->back();

    }
}
