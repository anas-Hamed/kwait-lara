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

    public function setup()
    {
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings(__('crud.user'), __('crud.users'));
        $this->crud->addClause('where', 'is_admin', false);
    }

    protected function setupListOperation()
    {
        // Avatar + Name as a combined closure column
        CRUD::addColumn([
            'name' => 'name',
            'label' => __('crud.name'),
            'type' => 'custom_html',
            'value' => function ($entry) {
                $initials = mb_strtoupper(mb_substr($entry->name, 0, 1));
                $colors = ['#0891b2', '#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'];
                $color = $colors[$entry->id % count($colors)];
                return '<div class="d-flex align-items-center gap-2">
                    <div class="user-avatar" style="background:' . $color . '">' . e($initials) . '</div>
                    <div>
                        <div class="fw-semibold">' . e($entry->name) . '</div>
                        <div class="text-muted small">' . e($entry->email) . '</div>
                    </div>
                </div>';
            },
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhere('name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('email', 'like', '%' . $searchTerm . '%');
            },
        ]);

        CRUD::addColumn([
            'name' => 'phone',
            'label' => __('crud.phone'),
            'type' => 'custom_html',
            'value' => function ($entry) {
                if (!$entry->phone) {
                    return '<span class="text-muted">—</span>';
                }
                return '<span dir="ltr">' . e($entry->phone) . '</span>';
            },
        ]);

        CRUD::addColumn([
            'name' => 'is_active',
            'label' => __('crud.status'),
            'type' => 'custom_html',
            'value' => function ($entry) {
                if ($entry->is_active) {
                    return '<span class="status-badge status-active">' . __('crud.active') . '</span>';
                }
                return '<span class="status-badge status-inactive">' . __('crud.inactive') . '</span>';
            },
        ]);

        CRUD::addColumn([
            'name' => 'created_at',
            'label' => __('crud.joined'),
            'type' => 'custom_html',
            'value' => function ($entry) {
                if (!$entry->created_at) {
                    return '<span class="text-muted">—</span>';
                }
                return '<div class="text-muted small">' . $entry->created_at->translatedFormat('d M Y') . '</div>';
            },
        ]);

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'active',
            'label' => __('crud.inactive_only'),
        ],
            false,
            function () {
                $this->crud->addClause('where', 'is_active', false);
            });

        $this->crud->setDefaultPageLength(15);
        $this->crud->orderBy('created_at', 'desc');
    }

    public function setupShowOperation()
    {
        CRUD::column('name')->label(__('crud.name'));
        CRUD::column('email')->label(__('crud.email'));
        CRUD::column('phone')->label(__('crud.phone'));
        CRUD::column('created_at')->label(__('crud.joined'));
    }

    public function setupUpdateOperation()
    {
        $this->crud->setValidation(UserRequest::class);
        $this->crud->addField([
            'name' => 'password',
            'label' => __('crud.password'),
            'type' => 'password',
            'wrapper' => ['class' => 'form-group col-md-6'],
        ]);
        $this->crud->addField([
            'name' => 'password_confirmation',
            'label' => __('crud.password_confirmation'),
            'type' => 'password',
            'wrapper' => ['class' => 'form-group col-md-6'],
        ]);
    }

    public function update()
    {
        $this->crud->validateRequest();
        $this->handlePasswordInput($this->crud->getRequest());
        $this->crud->unsetValidation();
        return $this->_update();
    }

    protected function handlePasswordInput($request)
    {
        $request->request->remove('password_confirmation');
        $request->request->remove('roles_show');
        $request->request->remove('permissions_show');

        if ($request->input('password')) {
            $request->request->add(['plaintext_password' => $request->input('password')]);
            $request->request->set('password', Hash::make($request->input('password')));
        } else {
            $request->request->remove('password');
        }
        return $request;
    }
}
