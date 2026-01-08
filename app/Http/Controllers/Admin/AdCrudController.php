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
        CRUD::setEntityNameStrings('إعلان', 'الإعلانات');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addFilter([
            'type' => 'dropdown',
            'name' => 'is_active',
            'label' => 'الحالة'
        ],
        [
            false => 'غير الفعال',
            true => 'الفعال'
        ],
        function ($value) {
            $this->crud->addClause('where', 'is_active', $value);
        });
        $this->crud->addColumn([
            'name' => 'title',
            'type' => 'text',
            'label' => 'الوصف'
        ]);

        $this->crud->addColumn([
            'name' => 'image',
            'type' => 'image',
            'width' => '200px',
            'height' => 'auto',
            'prefix' => '/storage/',
            'label' => 'الصورة'
        ]);


        $this->crud->addColumn([
            'name' => 'is_active',
            'type' => 'boolean',
            'label' => 'فعال؟'
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(AdRequest::class);
        $this->crud->setCreateContentClass('col-md-12');
        $this->crud->addField([
            'name' => 'title',
            'type' => 'text',
            'label' => 'الوصف'
        ]);
        $this->crud->addField([
            'name' => 'image',
            'type' => 'image',
            'label' => 'الصورة',
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
