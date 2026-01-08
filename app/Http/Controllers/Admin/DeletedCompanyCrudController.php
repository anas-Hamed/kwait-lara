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
        CRUD::setEntityNameStrings('شركة', 'الشركات المحذوفة');
    }

    protected function setupListOperation()
    {

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
        $this->crud->enableExportButtons();
        $this->crud->exportButtons();
    }

    protected function setupShowOperation()
    {

        $this->crud->setShowContentClass('col-md-12');
        $this->crud->set('show.setFromDb', false);
        CRUD::column('ar_name')->label('الاسم العربي');
        CRUD::column('en_name')->label('الاسم الانكليزي');
        CRUD::column('created_at')->label('تاريخ الإنشاء')->type('date');
        CRUD::column('is_trusted')->label('تم التوثيق؟')->type('boolean');
        CRUD::column('email')->label('البريد الالكتروني')->type('email');

        CRUD::column('user_id')->label('المستخدم');
        CRUD::column('category_id')->label('التصنيف');

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

    }
}
