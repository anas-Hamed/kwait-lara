<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PlanRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PlanCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PlanCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Plan::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/plan');
        CRUD::setEntityNameStrings(__('crud.plan'), __('crud.plans'));
    }

    protected function setupListOperation()
    {
        $this->initColumns();
    }

    protected function setupShowOperation()
    {
        $this->initColumns();
        $this->crud->addColumn([
            'name'  => 'description',
            'label' => __('crud.description'),
        ]);
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(PlanRequest::class);

        $this->crud->addField([
            'name'  => 'name',
            'label' => __('crud.name'),
        ]);
        $this->crud->addField([
            'name'  => 'description',
            'label' => __('crud.description'),
            'type'  => 'tinymce',
        ]);
        $this->crud->addField([
            'name'  => 'price',
            'label' => __('crud.price'),
            'type'  => 'number',
            'attributes' => ['step' => '0.01', 'min' => '0'],
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    private function initColumns()
    {
        $this->crud->addColumn([
            'name'  => 'name',
            'label' => __('crud.name'),
        ]);
        $this->crud->addColumn([
            'name'  => 'price',
            'label' => __('crud.price'),
        ]);
    }
}
