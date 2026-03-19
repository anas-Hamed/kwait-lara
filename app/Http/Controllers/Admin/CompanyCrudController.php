<?php

namespace App\Http\Controllers\Admin;


use App\Http\Requests\AdminUpdateCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Category;
use App\Models\Company;
use App\Models\ExtendedDatabaseNotification;
use App\Models\ImageItem;
use App\Models\User;
use App\Notifications\AdminApproveCompanyNotificationForUser;
use App\Notifications\CompanyActivatedNotificationForUser;
use App\Notifications\CompanyDisabledNotificationForUser;
use App\Notifications\CompanyTrustedNotificationForUser;
use App\Traits\ToggleActiveOperation;
use App\Traits\ToggleFeaturedOperation;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use Prologue\Alerts\Facades\Alert;

/**
 * Class CompanyCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CompanyCrudController extends CrudController
{

    use ListOperation, ShowOperation, UpdateOperation, ToggleActiveOperation, ToggleFeaturedOperation, ReorderOperation, FetchOperation {
        reorder as private _reorder;
        update as _update;
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Company::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/company');
        CRUD::setEntityNameStrings(__('crud.company'), __('crud.companies'));
    }

    protected function setupListOperation()
    {
        $this->crud->addButtonFromView('line', 'confirmPaid', 'confirmPaid');
        $this->initFilters();
        CRUD::column('ar_name')->label(__('crud.ar_name'));
        CRUD::column('en_name')->label(__('crud.en_name'));
        CRUD::column('user_id')->label(__('crud.user_name'));
        CRUD::column('category_id')->label(__('crud.category'));

        CRUD::addColumn([
            'name' => 'has_paid',
            'label' => __('crud.status'),
            'type' => 'custom_html',
            'value' => function ($entry) {
                if ($entry->has_paid) {
                    return '<span class="status-badge status-active">' . __('crud.paid') . '</span>';
                }
                return '<span class="status-badge status-inactive">' . __('crud.unpaid') . '</span>';
            },
        ]);

        CRUD::column('phone')->label(__('crud.phone'))->type('phone')->wrapper(['dir' => 'ltr']);
        CRUD::column('average_rate')->label(__('crud.avg_rating'));
        CRUD::column('created_at')->label(__('crud.created_at'))->type('date');
        $this->crud->enableExportButtons();
        $this->crud->exportButtons();
    }

    protected function setupShowOperation()
    {
        ExtendedDatabaseNotification::query()
            ->where('data->id', $this->crud->getCurrentEntryId())
            ->where('data->type', Company::class)->update([
                'read_at' => now()
            ]);


        $this->crud->addButtonFromView('line', 'confirmPaid', 'confirmPaid');

        $this->crud->setShowContentClass('col-md-12');
        $this->crud->set('show.setFromDb', false);
        CRUD::column('ar_name')->label(__('crud.ar_name'));
        CRUD::column('en_name')->label(__('crud.en_name'));
        CRUD::column('created_at')->label(__('crud.created_at'))->type('date');
        CRUD::column('is_trusted')->label(__('crud.is_trusted'))->type('boolean');
        CRUD::column('email')->label(__('crud.email'))->type('email');

        CRUD::column('user_id')->label(__('crud.user_name'));
        CRUD::column('category_id')->label(__('crud.category'));
        CRUD::column('has_paid')->type('boolean')->label(__('crud.is_paid'));
        CRUD::column('is_active')->type('boolean')->label(__('crud.is_active_label'));
        CRUD::column('average_rate')->label(__('crud.avg_rating'));

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


    public function confirmPaid($id)
    {
        $company = Company::query()->findOrFail($id);

        $company->has_paid = true;
        $company->save();
        Alert::success(__('crud.operation_success'))->flash();
        Notification::send($company->user, new AdminApproveCompanyNotificationForUser($company));
        return redirect()->back();

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
        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'is_active',
            'label' => __('crud.inactive_only'),
        ],
            false,
            function () {
                $this->crud->addClause('where', 'is_active', false);
            });

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'paid',
            'label' => __('crud.paid_only'),
        ],
            false,
            function () {
                $this->crud->addClause('where', 'has_paid', true);
            });
        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'not_paid',
            'label' => __('crud.unpaid_only'),
        ],
            false,
            function () {
                $this->crud->addClause('where', 'has_paid', false);
            });

    }


    protected function setupUpdateOperation()
    {
        $this->crud->setValidation(AdminUpdateCompanyRequest::class);
        $this->crud->setUpdateContentClass('col-md-12');
        $this->crud->addField([
            'name' => 'image',
            'type' => 'image',
            'prefix' => '/storage/',
            'crop' => true,
            'label' => __('crud.image'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);


        $this->crud->addField([
            'name' => 'custom',
            'type' => 'custom_html',
            'value' => "<hr>"
        ]);
        $this->crud->addField([
            'name' => 'ar_name',
            'label' => __('crud.ar_name'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);
        $this->crud->addField([
            'name' => 'en_name',
            'label' => __('crud.en_name'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'name' => 'custom2',
            'type' => 'custom_html',
            'value' => "<hr>"
        ]);

        $this->crud->addField([
            'name' => 'parent_id',
            'label' => __('crud.parent_category'),
            'model' => Category::class,
            'placeholder' => '',
            'minimum_input_length' => 0,
            'method' => 'post',
            'value' => $this->crud->getCurrentEntry()->category->parent_id,
            'data_source' => backpack_url('company/fetch/parent-category'),
            'attribute' => 'name',
            'type' => 'select2_from_ajax',
            'wrapper' => [
                'class' => 'form-group col-md-3'
            ]
        ]);
        $this->crud->addField([
            'name' => 'category_id',
            'label' => __('crud.sub_category'),
            'model' => Category::class,
            'placeholder' => '',
            'minimum_input_length' => 0,
            'method' => 'post',
            'include_all_form_fields' => true,
            'dependencies' => ['main_category_id'],
            'data_source' => backpack_url('company/fetch/category'),
            'attribute' => 'name',
            'type' => 'select2_from_ajax',
            'wrapper' => [
                'class' => 'form-group col-md-3'
            ]
        ]);

        $this->crud->addField([
            'name' => 'custom3',
            'type' => 'custom_html',
            'value' => "<hr>"
        ]);
        $this->crud->addField([
            'name' => 'email',
            'label' => __('crud.email'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);
        $this->crud->addField([
            'name' => 'phone',
            'type' => 'number',
            'label' => __('crud.phone'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);
        $this->crud->addField([
            'name' => 'whatsapp',
            'type' => 'number',
            'label' => __('crud.whatsapp'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'name' => 'about',
            'type' => 'textarea',
            'label' => __('crud.description'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ]
        ]);

        $this->crud->addField([
            'name' => 'website',
            'type' => 'url',
            'label' => __('crud.website'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'name' => 'facebook',
            'type' => 'url',
            'label' => __('crud.facebook'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'name' => 'twitter',
            'type' => 'url',
            'label' => __('crud.twitter'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'name' => 'snapchat',
            'type' => 'url',
            'label' => __('crud.snapchat'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'name' => 'instagram',
            'type' => 'url',
            'label' => __('crud.instagram'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'name' => 'linkedin',
            'type' => 'url',
            'label' => __('crud.linkedin'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'name' => 'images_',
            "relation" => "images",
            "attribute" => 'path',
            "type" => "multi_image_upload",
            "prefix" => "storage/",
            "aspect_ratio" => 10 / 3,
            'label' => __('crud.photos'),
        ]);
    }

    protected function setupReorderOperation()
    {
        $this->crud->set('reorder.label', 'ar_name');
        $this->crud->set('reorder.max_level', 1);
        $this->crud->addClause('where', 'category_id', request()->get('category_id'));
        $this->crud->addClause('where', 'is_featured', true);

    }

    public function saveReorder()
    {
        $count = 0;
        $items = \Request::input("tree");
        foreach ($items as $item) {
            if ($item["item_id"] != null) {
                $target = $this->crud->model::find($item["item_id"]);
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

    private
    function updateImages(): void
    {
        $images = $this->crud->getRequest()->input('images');
        $currentImagesIds = $this->crud->getCurrentEntry()->images()->pluck("id")->toArray();

        $comingImagesIds = [];
        $newImages = [];
        $imagesMostDelete = [];
        if ($images) {
            $images = array_map(function ($el) {
                return json_decode($el);
            }, $images);
            $comingImagesIds = array_filter($images, function ($el) {
                return $el->id != null;
            });
            $newImages = array_filter($images, function ($el) {
                return $el->id == null;
            });
            foreach ($currentImagesIds as $id) {
                if (!collect($comingImagesIds)->pluck("id")->contains($id)) {
                    $imagesMostDelete[] = $id;
                }
            }
        }
        $ImagePath = "company/" . $this->crud->entry->id;
        foreach ($newImages as $image) {
            $imageItem = ImageItem::withDestination($ImagePath, 10 / 3);
            $imageItem->path = $image->path;
            $imageItem->related_id = $this->crud->entry->id;
            $imageItem->related_type = Company::class;
            $imageItem->save();
        }
        foreach ($comingImagesIds as $item) {
            $image = ImageItem::query()->find($item->id);

            $image->update([
                "order" => $item->order
            ]);
        }
        foreach ($imagesMostDelete as $item) {
            ImageItem::destroy($item);
        }
    }

    public function update()
    {
        DB::beginTransaction();
        try {
            $res = $this->_update();
            $this->updateImages();
            DB::commit();
            return $res;
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            DB::rollBack();
            Alert::error(__('crud.edit_error'))->flash();
            return back();
        }
    }

    public function afterToggleActive($entry)
    {
        if ($entry->is_active) {
            Notification::send($entry->user, new CompanyActivatedNotificationForUser($entry));

        } else {
            Notification::send($entry->user, new CompanyDisabledNotificationForUser($entry));

        }
    }


    public function fetchParentCategory()
    {
        return $this->fetch([
            'model' => Category::class,
            'query' => function ($model) {
                return $model->whereNull('parent_id');
            }
        ]);
    }


    public function fetchCategory()
    {
        return $this->fetch([
            'model' => Category::class,
            'query' => function ($model) {
                $form = backpack_form_input();
                return $model->where('parent_id', $form['parent_id'] ?? 0);

            }
        ]);
    }
}
