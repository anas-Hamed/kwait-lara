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
        CRUD::setEntityNameStrings('شركة', 'الشركات');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addButtonFromView('line', 'confirmPaid', 'confirmPaid');
        $this->initFilters();
        CRUD::column('ar_name')->label('الاسم العربي');
        CRUD::column('en_name')->label('الاسم الانكليزي');
        CRUD::column('email')->label('البريد الالكتروني');
        CRUD::column('user_id')->label('المستخدم');
        CRUD::column('category_id')->label('التصنيف');
        CRUD::column('email')->label('البريد الالكتروني')->type('email');
        CRUD::column('phone')->label('الهاتف')->type('phone')->wrapper([
            'dir' => 'ltr'
        ]);
        CRUD::column('whatsapp')->label('واتساب')->type('whatsapp')->wrapper([
            'dir' => 'ltr'
        ]);
        CRUD::column('average_rate')->label('متوسط التقييم');
        CRUD::column('created_at')->label('تاريخ الإنشاء')->type('date');
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
        CRUD::column('ar_name')->label('الاسم العربي');
        CRUD::column('en_name')->label('الاسم الانكليزي');
        CRUD::column('created_at')->label('تاريخ الإنشاء')->type('date');
        CRUD::column('is_trusted')->label('تم التوثيق؟')->type('boolean');
        CRUD::column('email')->label('البريد الالكتروني')->type('email');

        CRUD::column('user_id')->label('المستخدم');
        CRUD::column('category_id')->label('التصنيف');
        CRUD::column('has_paid')->type('boolean')->label('تم الدفع؟');
        CRUD::column('is_active')->type('boolean')->label('فعال؟');
        CRUD::column('average_rate')->label('متوسط التقييم');

        CRUD::column('phone')->label('الهاتف')->type('phone')->wrapper([
            'dir' => 'ltr'
        ]);
        CRUD::column('whatsapp')->label('واتساب')->type('whatsapp')->wrapper([
            'dir' => 'ltr'
        ]);
        CRUD::column('website')->label('الموقع الإلكتروني')->type('url');
        CRUD::column('twitter')->label('تويتر')->type('url');
        CRUD::column('facebook')->label('فيسبوك')->type('url');
        CRUD::column('instagram')->label('انستجرام')->type('url');
        CRUD::column('snapchat')->label('سناب تشات')->type('url');
        CRUD::column('linkedin')->label('لينكد إن')->type('url');
        CRUD::column('about')->label('حول');
        CRUD::column('tags')->type('array_options')->label('الكلمات المفتاحية');


        $this->crud->addColumn([
            'name' => 'work_times',
            'label' => 'أوقات العمل',
            'type' => 'work_times'
        ]);
        $this->crud->addColumn([
            'name' => 'image',
            'type' => 'image',
            'width' => '200px',
            'height' => 'auto',
            'label' => 'الشعار',
            'prefix' => 'storage/'
        ]);
        if ($this->crud->getCurrentEntry()->images()->count()) {
            $this->crud->addColumn(
                [
                    "name" => "images",
                    "type" => "imageSlider",
                    "label" => "الصور",
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
                    "label" => "الموقع"
                ]
            );
        }

    }


    public function confirmPaid($id)
    {
        $company = Company::query()->findOrFail($id);

        $company->has_paid = true;
        $company->save();
        Alert::success('تمت العملية بنجاح')->flash();
        Notification::send($company->user, new AdminApproveCompanyNotificationForUser($company));
        return redirect()->back();

    }

    private function initFilters()
    {
        $this->crud->addFilter([
            'name' => 'user_id',
            'type' => 'select2',
            'label' => 'المستخدم'
        ], function () {
            return User::where('is_admin', 0)->pluck('name', 'id')->toArray();
        }, function ($value) { // if the filter is active
            $this->crud->addClause('where', 'user_id', $value);
        });

        $this->crud->addFilter([
            'name' => 'category_id',
            'type' => 'select2',
            'label' => 'التصنيف'
        ], function () {
            return Category::where('parent_id', '!=', null)->pluck('name', 'id')->toArray();
        }, function ($value) { // if the filter is active
            $this->crud->addClause('where', 'category_id', $value);
        });
        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'is_active',
            'label' => 'غير الفعال فقط'
        ],
            false,
            function () {
                $this->crud->addClause('where', 'is_active', false);
            });

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'paid',
            'label' => 'تم الدفع فقط'
        ],
            false,
            function () {
                $this->crud->addClause('where', 'has_paid', true);
            });
        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'not_paid',
            'label' => 'لم يتم الدفع فقط'
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
            'label' => 'الصورة',
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
            'label' => 'الاسم العربي',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);
        $this->crud->addField([
            'name' => 'en_name',
            'label' => 'الاسم الانكليزي',
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
            'label' => 'التصنيف الأساسي',
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
            'label' => 'التصنيف الفرعي',
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
            'label' => 'البريد',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);
        $this->crud->addField([
            'name' => 'phone',
            'type' => 'number',
            'label' => 'الهاتف',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);
        $this->crud->addField([
            'name' => 'whatsapp',
            'type' => 'number',
            'label' => 'الواتساب',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'name' => 'about',
            'type' => 'textarea',
            'label' => 'الوصف',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ]
        ]);

        $this->crud->addField([
            'name' => 'website',
            'type' => 'url',
            'label' => 'الموقع الإلكتروني',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'name' => 'facebook',
            'type' => 'url',
            'label' => 'فيسبوك',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'name' => 'twitter',
            'type' => 'url',
            'label' => 'تويتر',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'name' => 'snapchat',
            'type' => 'url',
            'label' => 'سناب شات',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'name' => 'instagram',
            'type' => 'url',
            'label' => 'انستاغرام',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'name' => 'linkedin',
            'type' => 'url',
            'label' => 'لينكد إن',
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
            'label' => 'الصور',
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
            Alert::error('خطأ في التعديل')->flash();
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
