<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SubscriptionRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SubscriptionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SubscriptionCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Subscription::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/subscription');
        CRUD::setEntityNameStrings(__('crud.subscription'), __('crud.subscriptions'));
    }

    protected function setupListOperation()
    {
        $this->initColumns();
    }

    protected function setupShowOperation()
    {
        $this->initColumns();
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(SubscriptionRequest::class);

        $this->crud->addField([
            'name'        => 'company_id',
            'label'       => __('crud.company'),
            'type'        => 'select2',
            'entity'      => 'company',
            'attribute'   => 'ar_name',
            'model'       => \App\Models\Company::class,
        ]);

        $this->crud->addField([
            'name'      => 'plan_id',
            'label'     => __('crud.plan'),
            'type'      => 'select2',
            'entity'    => 'plan',
            'attribute' => 'name',
            'model'     => \App\Models\Plan::class,
        ]);

        $this->crud->addField([
            'name'    => 'is_active',
            'label'   => __('crud.active'),
            'type'    => 'boolean',
            'default' => true,
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    private function initColumns()
    {
        $this->crud->addColumn([
            'name'      => 'company',
            'label'     => __('crud.company'),
            'type'      => 'relationship',
            'attribute' => 'ar_name',
        ]);

        $this->crud->addColumn([
            'name'      => 'plan',
            'label'     => __('crud.plan'),
            'type'      => 'relationship',
            'attribute' => 'name',
        ]);

        $this->crud->addColumn([
            'name'  => 'is_active',
            'label' => __('crud.active'),
            'type'  => 'boolean',
        ]);
    }
}
