<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\QaRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class QaItemCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class QaItemCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\QaItem::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/qa-item');
        CRUD::setEntityNameStrings(__('crud.qa'), __('crud.qas'));
    }

    protected function setupListOperation()
    {
        $this->initColumns();
    }

    protected function setupShowOperation()
    {
        $this->initColumns();
        $this->crud->addColumn([
            'name'  => 'answer',
            'label' => __('crud.answer'),
            'type'  => 'html',
        ]);
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(QaRequest::class);

        $this->crud->addField([
            'name'      => 'category_id',
            'label'     => __('crud.category'),
            'type'      => 'select2',
            'entity'    => 'category',
            'attribute' => 'name',
            'model'     => \App\Models\Category::class,
            'options'   => function ($query) {
                return $query->whereNull('parent_id')->whereIsActive(true)->orderBy('order')->get();
            },
        ]);

        $this->crud->addField([
            'name'  => 'question',
            'label' => __('crud.question'),
            'type'  => 'text',
        ]);

        $this->crud->addField([
            'name'  => 'answer',
            'label' => __('crud.answer'),
            'type'  => 'tinymce',
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    private function initColumns()
    {
        $this->crud->addColumn([
            'name'      => 'category',
            'label'     => __('crud.category'),
            'type'      => 'relationship',
            'attribute' => 'name',
        ]);

        $this->crud->addColumn([
            'name'  => 'question',
            'label' => __('crud.question'),
        ]);
    }
}
