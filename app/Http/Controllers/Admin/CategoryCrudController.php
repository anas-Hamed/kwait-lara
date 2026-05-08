<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Traits\BilingualFieldsTrait;
use App\Traits\ToggleActiveOperation;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Prologue\Alerts\Facades\Alert;

/**
 * Class CategoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CategoryCrudController extends CrudController
{
    use ListOperation;
    use CreateOperation {
        store as _store;
    }
    use UpdateOperation {
        update as _update;
    }
    use DeleteOperation;
    use ShowOperation;
    use ReorderOperation{
        reorder as _reorder;
    }
    use ToggleActiveOperation;
    use BilingualFieldsTrait;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Category::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/category');
        CRUD::setEntityNameStrings(__('crud.category_single'), __('crud.categories'));
        $this->crud->addClause('orderBy', 'order');
        $this->crud->with(['parent']);
        $this->crud->query->withCount('companies')->withCount('children');

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'show_all',
            'label' => __('crud.show_all_categories'),
        ],
            false,
            function () {
                // active: show every category (incl. sub-categories)
            },
            function () {
                // default (filter not active): main categories only
                if (!request()->filled('parent_id')) {
                    $this->crud->addClause('where', 'parent_id', null);
                }
            });

        $this->crud->addFilter([
            'name' => 'parent_id',
            'label' => __('crud.parent_category'),
        ], Category::where('parent_id', null)->pluck('name', 'id')->toArray(),
            function ($value) {
                $this->crud->addClause('where', 'parent_id', (int)$value);
            });
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


    }

    public function setupShowOperation()
    {
        $this->initColumns();
    }

    public function initColumns()
    {
        CRUD::column('name')->label(__('crud.name'));
        CRUD::addColumn([
            'name' => 'parent',
            'label' => __('crud.parent'),
            'type' => 'custom_html',
            'value' => function ($entry) {
                if ($entry->parent_id === null) {
                    return '<span class="badge badge-info">' . __('crud.main_category') . '</span>';
                }
                return e(optional($entry->parent)->name ?? '—');
            },
        ]);
        CRUD::addColumn([
            'name' => 'companies_count',
            'label' => __('crud.companies_count'),
            'type' => 'text',
        ]);
        CRUD::addColumn([
            'name' => 'children_count',
            'label' => __('crud.sub_category'),
            'type' => 'text',
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
        CRUD::column('order')->label(__('crud.priority'));
        $this->crud->addColumn(['name' => 'image', 'type' => 'image', 'width' => '50px', 'height' => 'auto', 'label' => __('crud.icon'), 'prefix' => 'storage/']);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->addField([
            'label' => __('crud.parent_category'),
            'type' => 'select2',
            'name' => 'parent_id',
            'options' => function ($query) {
                return $query->orderBy('name', 'ASC')->where('parent_id', null)->get();
            }]);
        $this->initFields();

    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        if ($this->crud->getCurrentEntry()->children()->count() == 0) {
            $this->crud->addField(
                [
                    'label' => __('crud.parent_category'),
                    'type' => 'select2',
                    'name' => 'parent_id',
                    'options' => (function ($query) { return $query->orderBy('name', 'ASC')->where('id', '<>', $this->crud->getCurrentEntry()->id)->where('parent_id', null)->get();})
                ]
            );
        }
        $this->initFields();

    }

    public function initFields()
    {
        CRUD::setValidation(CategoryRequest::class);
        $this->addBilingualField('name', __('crud.name'), 'text');
        $this->crud->addField([
            'name' => 'image',
            'type' => 'image',
            'label' => __('crud.logo'),
            'crop' => true,
            'upload' => true,
            'prefix' => 'storage/',
            'aspect_ratio' => 1,
            'wrapperAttributes' => [
                'class' => 'col-md-6  form-group'
            ]
        ]);

    }

    public function store()
    {
        $request = $this->crud->getRequest();
        $request->request->add(['name' => $request->input('name_ar')]);

        $response = $this->_store();
        $entry = $this->crud->getCurrentEntry() ?: $this->crud->entry;
        if ($entry) {
            $this->saveBilingualTranslations($entry, ['name']);
        }
        return $response;
    }

    public function update()
    {
        $request = $this->crud->getRequest();
        $request->request->add(['name' => $request->input('name_ar')]);

        $response = $this->_update();
        $entry = $this->crud->getCurrentEntry();
        if ($entry) {
            $this->saveBilingualTranslations($entry, ['name']);
        }
        return $response;
    }

    public function toggleActive($id)
    {
        $category = Category::query()->findOrFail($id);
        $category->is_active = !$category->is_active;
        $category->save();
        Alert::success(__('crud.operation_success'))->flash();
        return redirect()->back();

    }
    protected function setupReorderOperation()
    {
        $this->crud->set('reorder.label', 'name');
        $this->crud->set('reorder.max_level', 2);
    }

    public function saveReorder()
    {
        $count = 0;
        $items = \Request::input("tree");
        if (is_string($items)) {
            $items = json_decode($items, true) ?: [];
        }
        if (!is_array($items)) {
            return;
        }
        foreach ($items as $item) {
            if (!is_array($item) || empty($item['item_id'])) {
                continue;
            }
            $target = Category::find($item['item_id']);
            if (!$target) {
                continue;
            }
            $newParentId = isset($item['parent_id']) && $item['parent_id'] !== '' && $item['parent_id'] !== null
                ? (int)$item['parent_id']
                : null;
            $target->parent_id = $newParentId;
            $target->order = $count + 1;
            $target->save();
            $count++;
        }
    }

    public function reorder()
    {
        return $this->_reorder()->with('sort_by', 'order');
    }

}
