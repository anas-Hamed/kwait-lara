<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AdRequest;
use App\Models\Ad;
use App\Traits\ToggleActiveOperation;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AdCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AdCrudController extends CrudController
{
    use ListOperation, CreateOperation, DeleteOperation,ToggleActiveOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(Ad::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/ad');
        CRUD::setEntityNameStrings(__('crud.ad'), __('crud.ads'));
    }

    protected function setupListOperation()
    {
        $this->crud->addFilter([
            'type' => 'dropdown',
            'name' => 'is_active',
            'label' => __('crud.status'),
        ],
        [
            false => __('crud.inactive'),
            true => __('crud.active'),
        ],
        function ($value) {
            $this->crud->addClause('where', 'is_active', $value);
        });
        $this->crud->addColumn([
            'name' => 'title',
            'type' => 'text',
            'label' => __('crud.description'),
        ]);

        $this->crud->addColumn([
            'name' => 'image',
            'type' => 'image',
            'width' => '200px',
            'height' => 'auto',
            'prefix' => '/storage/',
            'label' => __('crud.image'),
        ]);

        $this->crud->addColumn([
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
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(AdRequest::class);
        $this->crud->setCreateContentClass('col-md-12');
        $this->crud->addField([
            'name' => 'title',
            'type' => 'text',
            'label' => __('crud.description'),
        ]);
        $this->crud->addField([
            'name' => 'image',
            'type' => 'image',
            'label' => __('crud.image'),
            'crop' => true,
            'upload' => true,
            'prefix' => '/storage/',
            'aspect_ratio' => 10 / 4,
            'wrapperAttributes' => [
                'class' => 'col-md-12  form-group'
            ]
        ]);
    }
}
