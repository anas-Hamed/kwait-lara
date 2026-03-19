<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DeletedCompanyRequest;
use App\Models\Category;
use App\Models\DeletedCompany;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class DeletedCompanyCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DeletedCompanyCrudController extends CrudController
{
    use ListOperation, ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(DeletedCompany::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/deleted-company');
        CRUD::setEntityNameStrings(__('crud.deleted_company'), __('crud.deleted_companies'));
    }

    protected function setupListOperation()
    {
        $this->initFilters();
        CRUD::column('ar_name')->label(__('crud.ar_name'));
        CRUD::column('en_name')->label(__('crud.en_name'));
        CRUD::column('user_id')->label(__('crud.user_name'));
        CRUD::column('category_id')->label(__('crud.category'));
        CRUD::column('phone')->label(__('crud.phone'))->type('phone')->wrapper(['dir' => 'ltr']);
        CRUD::column('whatsapp')->label(__('crud.whatsapp'))->type('whatsapp')->wrapper(['dir' => 'ltr']);
        $this->crud->enableExportButtons();
        $this->crud->exportButtons();
    }

    protected function setupShowOperation()
    {

        $this->crud->setShowContentClass('col-md-12');
        $this->crud->set('show.setFromDb', false);
        CRUD::column('ar_name')->label(__('crud.ar_name'));
        CRUD::column('en_name')->label(__('crud.en_name'));
        CRUD::column('created_at')->label(__('crud.created_at'))->type('date');
        CRUD::column('is_trusted')->label(__('crud.is_trusted'))->type('boolean');
        CRUD::column('email')->label(__('crud.email'))->type('email');

        CRUD::column('user_id')->label(__('crud.user_name'));
        CRUD::column('category_id')->label(__('crud.category'));

        CRUD::column('phone')->label(__('crud.phone'))->type('phone')->wrapper([
            'dir' => 'ltr'
        ]);
        CRUD::column('whatsapp')->label(__('crud.whatsapp'))->type('whatsapp')->wrapper([
            'dir' => 'ltr'
        ]);
        CRUD::column('website')->label(__('crud.website'))->type('url');
        CRUD::column('twitter')->label(__('crud.twitter'))->type('url');
        CRUD::column('facebook')->label(__('crud.facebook'))->type('url');
        CRUD::column('instagram')->label(__('crud.instagram'))->type('url');
        CRUD::column('snapchat')->label(__('crud.snapchat'))->type('url');
        CRUD::column('linkedin')->label(__('crud.linkedin'))->type('url');
        CRUD::column('about')->label(__('crud.about'));
        CRUD::column('tags')->type('array_options')->label(__('crud.tags'));

        $this->crud->addColumn([
            'name' => 'work_times',
            'label' => __('crud.work_times'),
            'type' => 'work_times'
        ]);

        $this->crud->addColumn([
            'name' => 'image',
            'type' => 'image',
            'width' => '200px',
            'height' => 'auto',
            'label' => __('crud.logo'),
            'prefix' => 'storage/'
        ]);

        if ($this->crud->getCurrentEntry()->images()->count()) {
            $this->crud->addColumn(
                [
                    "name" => "images",
                    "type" => "imageSlider",
                    "label" => __('crud.photos'),
                    "entity" => 'images',
                    'attribute' => 'path',
                    'ratio' => 40
                ]
            );
        }
        if ($this->crud->getCurrentEntry()->location != null) {
            $this->crud->addColumn(
                [
                    "name" => "location",
                    "type" => "location",
                    "label" => __('crud.location')
                ]
            );
        }

    }

    private function initFilters()
    {
        $this->crud->addFilter([
            'name' => 'user_id',
            'type' => 'select2',
            'label' => __('crud.user_name'),
        ], function () {
            return User::where('is_admin', 0)->pluck('name', 'id')->toArray();
        }, function ($value) {
            $this->crud->addClause('where', 'user_id', $value);
        });

        $this->crud->addFilter([
            'name' => 'category_id',
            'type' => 'select2',
            'label' => __('crud.category'),
        ], function () {
            return Category::where('parent_id', '!=', null)->pluck('name', 'id')->toArray();
        }, function ($value) {
            $this->crud->addClause('where', 'category_id', $value);
        });

    }
}
