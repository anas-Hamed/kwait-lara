<?php

namespace App\Http\Controllers\Admin;


use App\Http\Requests\AdminUpdateCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Category;
use App\Models\Company;
use App\Models\DeletedCompany;
use App\Models\ExtendedDatabaseNotification;
use App\Models\ImageItem;
use App\Models\User;
use App\Notifications\AdminApproveCompanyNotificationForUser;
use App\Notifications\AdminDirectMessageToCompanyOwner;
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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $this->crud->addButtonFromView('line', 'companyActions', 'companyActions', 'end');
        $this->crud->enablePersistentTable();
        $this->crud->setOperationSetting('responsiveTable', true);
        $this->initFilters();
        $this->crud->with(['user:id,name,phone,email', 'category:id,name']);

        CRUD::addColumn([
            'name' => 'ar_name',
            'label' => __('crud.ar_name'),
            'searchLogic' => function ($query, $column, $searchTerm) {
                $term = '%' . $searchTerm . '%';
                $query->orWhere('ar_name', 'like', $term)
                    ->orWhere('en_name', 'like', $term)
                    ->orWhere('slug', 'like', $term)
                    ->orWhere('about', 'like', $term)
                    ->orWhere('tags', 'like', $term)
                    ->orWhere('email', 'like', $term)
                    ->orWhere('phone', 'like', $term)
                    ->orWhere('whatsapp', 'like', $term)
                    ->orWhereHas('user', function ($q) use ($term) {
                        $q->where('name', 'like', $term)
                            ->orWhere('email', 'like', $term)
                            ->orWhere('phone', 'like', $term);
                    });
            },
        ]);
        CRUD::column('en_name')->label(__('crud.en_name'));

        CRUD::addColumn([
            'name' => 'user',
            'label' => __('crud.user_name'),
            'type' => 'custom_html',
            'value' => function ($entry) {
                if (!$entry->user) {
                    return '<span class="text-muted">—</span>';
                }
                $url = backpack_url('user/' . $entry->user->id . '/show');
                return '<a href="' . e($url) . '">' . e($entry->user->name) . '</a>';
            },
        ]);

        CRUD::addColumn([
            'name' => 'category',
            'label' => __('crud.category'),
            'type' => 'custom_html',
            'value' => function ($entry) {
                return $entry->category ? e($entry->category->name) : '<span class="text-muted">—</span>';
            },
        ]);

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

        CRUD::addColumn([
            'name' => 'disabled_until',
            'label' => __('crud.disabled_until'),
            'type' => 'custom_html',
            'value' => function ($entry) {
                if (!$entry->disabled_until) {
                    return '<span class="text-muted">—</span>';
                }
                return '<span class="status-badge status-inactive">' . e($entry->disabled_until->translatedFormat('d M Y H:i')) . '</span>';
            },
        ]);

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
        $this->crud->addButtonFromView('line', 'companyActions', 'companyActions', 'end');

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


    public function temporaryDisable(Request $request, $id)
    {
        $request->validate([
            'until' => 'required|date|after:now',
        ]);
        $company = Company::query()->findOrFail($id);
        $company->is_active = false;
        $company->disabled_until = $request->input('until');
        $company->save();
        try {
            Notification::send($company->user, new CompanyDisabledNotificationForUser($company));
        } catch (\Throwable $e) {
            Log::error('Notification failed: ' . $e->getMessage());
        }
        Alert::success(__('crud.operation_success'))->flash();
        return redirect()->back();
    }

    public function sendDirectMessage(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:120',
            'body' => 'required|string|max:2000',
        ]);
        $company = Company::query()->findOrFail($id);
        if (!$company->user) {
            Alert::error(__('crud.no_owner_for_company'))->flash();
            return redirect()->back();
        }
        try {
            Notification::send(
                $company->user,
                new AdminDirectMessageToCompanyOwner($request->input('title'), $request->input('body'), $company)
            );
            Alert::success(__('crud.notification_sent'))->flash();
        } catch (\Throwable $e) {
            Log::error('Direct message failed: ' . $e->getMessage());
            Alert::error(__('crud.operation_failed'))->flash();
        }
        return redirect()->back();
    }

    public function adminDelete($id)
    {
        $company = Company::query()->findOrFail($id);
        DB::beginTransaction();
        try {
            $deleted = DeletedCompany::query()->create($company->toArray());

            $company->images()->each(function ($el) use ($deleted) {
                $el->update([
                    'related_type' => DeletedCompany::class,
                    'related_id' => $deleted->id,
                ]);
            });

            $company->workTimes()->each(function ($el) use ($deleted) {
                $deleted->workTimes()->create([
                    'day' => $el->day,
                    'start_time' => $el->start_time,
                    'end_time' => $el->end_time,
                    'active' => $el->active,
                ]);
                $el->delete();
            });

            $company->updates()->delete();
            $company->rates()->delete();
            $company->favorites()->delete();
            $company->trustRequest()->delete();
            $company->subscriptions()->delete();
            $company->delete();

            DB::commit();
            Alert::success(__('crud.delete_success'))->flash();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Admin delete company failed: ' . $e->getMessage());
            Alert::error(__('crud.operation_failed'))->flash();
            return redirect()->back();
        }
        return redirect(backpack_url('company'));
    }

    public function confirmPaid($id)
    {
        $company = Company::query()->findOrFail($id);

        $company->has_paid = true;
        $company->save();
        Alert::success(__('crud.operation_success'))->flash();
        try {
            Notification::send($company->user, new AdminApproveCompanyNotificationForUser($company));
        } catch (\Throwable $e) {
            Log::error('Notification failed: ' . $e->getMessage());
        }
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
            'name' => 'created_range',
            'type' => 'date_range',
            'label' => __('crud.created_at'),
        ],
            false,
            function ($value) {
                $dates = json_decode($value);
                if (!empty($dates->from)) {
                    $this->crud->addClause('where', 'created_at', '>=', $dates->from);
                }
                if (!empty($dates->to)) {
                    $this->crud->addClause('where', 'created_at', '<=', $dates->to . ' 23:59:59');
                }
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

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'has_active_subscription',
            'label' => __('crud.subscribed_only'),
        ],
            false,
            function () {
                $this->crud->addClause('whereHas', 'subscriptions', function ($q) {
                    $q->where('is_active', true);
                });
            });

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'is_featured',
            'label' => __('crud.featured'),
        ],
            false,
            function () {
                $this->crud->addClause('where', 'is_featured', true);
            });

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'is_trusted',
            'label' => __('crud.is_trusted'),
        ],
            false,
            function () {
                $this->crud->addClause('where', 'is_trusted', true);
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
        $categoryId = request()->get('category_id');
        if (!empty($categoryId)) {
            $this->crud->addClause('where', 'category_id', (int)$categoryId);
        }
        $this->crud->addClause('where', 'is_featured', true);
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
            $target = $this->crud->model::find($item['item_id']);
            if (!$target) {
                continue;
            }
            $target->order = $count + 1;
            $target->save();
            $count++;
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
            try {
                Notification::send($entry->user, new CompanyActivatedNotificationForUser($entry));
            } catch (\Throwable $e) {
                Log::error('Notification failed: ' . $e->getMessage());
            }
        } else {
            try {
                Notification::send($entry->user, new CompanyDisabledNotificationForUser($entry));
            } catch (\Throwable $e) {
                Log::error('Notification failed: ' . $e->getMessage());
            }
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
