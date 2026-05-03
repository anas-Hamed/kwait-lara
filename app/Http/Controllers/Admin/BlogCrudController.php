<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BlogRequest;
use App\Traits\BilingualFieldsTrait;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Str;

/**
 * Class BlogCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BlogCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as _store;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation{
        update as _update;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use BilingualFieldsTrait;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Blog::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/blog');
        CRUD::setEntityNameStrings(__('crud.blog'), __('crud.blogs'));
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->initColumn();
    }
    protected function setupShowOperation()
    {
        $this->crud->setShowContentClass('col-md-12');
        $this->crud->set('show.setFromDb',false);
        $this->initColumn();
        $this->crud->addColumn([
            'name' => 'text',
            'label' => __('crud.article'),
            'type' => 'custom_html',
            'value' => $this->crud->getCurrentEntry()->text
        ]);
        $this->crud->addButtonFromModelFunction('line','showOnWebsite','showOnWebsite');
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(BlogRequest::class);
        $this->crud->setCreateContentClass('col-md-12');

        $this->addBilingualField('title', __('crud.title'), 'text');
        $this->addBilingualField('text', __('crud.article'), 'tinymce', [
            'wrapperAttributes' => ['class' => 'col-md-12 form-group bilingual-field'],
        ]);

        $this->crud->addField([
            'name' => 'image',
            'type' => 'image',
            'label' => __('crud.image'),
            'upload' => true,
            'crop' => true ,
            'aspect_ratio' => 16/9,
            'prefix' => 'storage/'
        ]);
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->crud->setUpdateContentClass('col-md-12');
        $this->setupCreateOperation();
    }
    private function initColumn()
    {
        $this->crud->addColumn([
            'name' => 'title',
            'label' => __('crud.title')
        ]);

        $this->crud->addColumn([
            'name' => 'image',
            'label' => __('crud.image'),
            'type' => 'image',
            'prefix' => 'storage/'
        ]);
    }

    public function store()
    {
        $request = $this->crud->getRequest();
        $request->request->add([
            'slug'  => Str::slug($request->input('title_en') ?: $request->input('title_ar')),
            'title' => $request->input('title_ar'),
            'text'  => $request->input('text_ar'),
        ]);
        $this->crud->addField(['name' => 'slug', 'type' => 'hidden']);

        $response = $this->_store();
        $entry = $this->crud->getCurrentEntry() ?: $this->crud->entry;
        if ($entry) {
            $this->saveBilingualTranslations($entry, ['title', 'text']);
        }

        return $response;
    }

    public function update()
    {
        $request = $this->crud->getRequest();
        $request->request->add([
            'slug'  => Str::slug($request->input('title_en') ?: $request->input('title_ar')),
            'title' => $request->input('title_ar'),
            'text'  => $request->input('text_ar'),
        ]);
        $this->crud->addField(['name' => 'slug', 'type' => 'hidden']);

        $response = $this->_update();
        $entry = $this->crud->getCurrentEntry();
        if ($entry) {
            $this->saveBilingualTranslations($entry, ['title', 'text']);
        }

        return $response;
    }
}
