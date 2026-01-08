<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Constants;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Traits\ToggleActiveOperation;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Hash;
use Prologue\Alerts\Facades\Alert;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController
{
    use ListOperation;
    use ShowOperation;
    use UpdateOperation {
        update as _update;
    }
    use ToggleActiveOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('مستخدم', 'المستخدمين');
        $this->crud->addClause('where', 'is_admin', false);
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
            'label' => 'غير الفعال فقط'
        ],
            false,
            function () {
                $this->crud->addClause('where', 'is_active', false);
            });
    }

    public function setupShowOperation()
    {
        $this->initColumns();
    }

    public function setupUpdateOperation()
    {
        $this->crud->setValidation(UserRequest::class);
        $this->crud->addField([
            'name' => 'password',
            'label' => "كلمة المرور",
            'type' => 'password',
            'wrapper' => ['class' => 'form-group col-md-6']
        ]);
        $this->crud->addField([
            'name' => 'password_confirmation',
            'label' => "تأكيد كلمة المرور",
            'type' => 'password',
            'wrapper' => ['class' => 'form-group col-md-6']
        ]);
    }

    private function initColumns()
    {
        CRUD::column('name')->label('الاسم');
        CRUD::column('email')->label('البريد الالكتروني');
        CRUD::column('phone')->label('الهاتف');
        CRUD::column('created_at')->label('تاريخ الإنشاء');

    }


    public function update()
    {
        $this->crud->validateRequest();
        $this->handlePasswordInput($this->crud->getRequest());
        $this->crud->unsetValidation();
        return $this->_update();
    }

    /**
     * Handle password input fields.
     */
    protected function handlePasswordInput($request)
    {
        // Remove fields not present on the user.
        $request->request->remove('password_confirmation');
        $request->request->remove('roles_show');
        $request->request->remove('permissions_show');

        // Encrypt password if specified.
        if ($request->input('password')) {
            $request->request->add(['plaintext_password' => $request->input('password')]);
            $request->request->set('password', Hash::make($request->input('password')));
        } else {
            $request->request->remove('password');
        }
        return $request;
    }
}
