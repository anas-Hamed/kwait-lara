<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
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
    use CreateOperation;
    use UpdateOperation;
    use DeleteOperation;
    use ShowOperation;
    use ReorderOperation{
        reorder as _reorder;
    }
    use ToggleActiveOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Category::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/category');
        CRUD::setEntityNameStrings('تصنيف', 'التصنيفات');
        $this->crud->addClause('orderBy','order');
        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'parent_only',
            'label' => 'التصنيفات الأساسية'
        ],
            false,
            function () {
                $this->crud->addClause('where', 'parent_id', null);
            });

        $this->crud->addFilter([
            'name' => 'parent_id',
            'label' => 'التصنيف الأب',
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
        CRUD::column('name')->label('الاسم');
        CRUD::column('parent_id')->label('الأب');
        CRUD::column('is_active')->type('boolean')->label('فعال؟');
        CRUD::column('order')->label('الأولوية');
        $this->crud->addColumn(['name' => 'image', 'type' => 'image', 'width' => '50px', 'height' => 'auto', 'label' => 'الرمز', 'prefix' => 'storage/']);
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
            'label' => "التصنيف الأب",
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
                    'label' => "التصنيف الأب",
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
        CRUD::field('name')->label('الاسم')->type('text');
        $this->crud->addField([
            'name' => 'image',
            'type' => 'image',
            'label' => 'الشعار',
            'crop' => true,
            'upload' => true,
            'prefix' => 'storage/',
            'aspect_ratio' => 1,
            'wrapperAttributes' => [
                'class' => 'col-md-6  form-group'
            ]
        ]);

    }

    public function toggleActive($id)
    {
        $category = Category::query()->findOrFail($id);
        $category->is_active = !$category->is_active;
        $category->save();
        Alert::success('تمت العملية بنجاح')->flash();
        return redirect()->back();

    }
    protected function setupReorderOperation()
    {
        $this->crud->set('reorder.label', 'name');
        $this->crud->set('reorder.max_level', 0);

    }
    public function saveReorder()
    {
        $count = 0;
        $items = \Request::input("tree");
        foreach ($items as $item){
            if ($item["item_id"] != null){
                $target = Category::find($item["item_id"]);
                $target->order = $count + 1;
                $target->save();
                $count++;
            }
        }
    }

    public function reorder()
    {
        return $this->_reorder()->with('sort_by', 'order');
    }

}
