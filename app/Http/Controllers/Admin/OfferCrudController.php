<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use App\Traits\ToggleActiveOperation;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class OfferCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class OfferCrudController extends CrudController
{
    use ListOperation, ShowOperation, ToggleActiveOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Offer::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/offer');
        CRUD::setEntityNameStrings(__('crud.offer'), __('crud.offers'));
        // Companies create/edit offers via the mobile API; admin is read-only + moderation.
        CRUD::denyAccess(['create', 'update', 'delete']);
    }

    protected function setupListOperation()
    {
        $this->crud->setOperationSetting('responsiveTable', true);
        $this->initFilters();
        $this->crud->with(['company:id,ar_name,en_name']);

        CRUD::addColumn([
            'name' => 'company',
            'label' => __('crud.company'),
            'type' => 'custom_html',
            'value' => function ($entry) {
                if (!$entry->company) {
                    return '<span class="text-muted">—</span>';
                }
                $url = backpack_url('company/' . $entry->company->id . '/show');
                return '<a href="' . e($url) . '">' . e($entry->company->ar_name) . '</a>';
            },
        ]);

        CRUD::column('ar_title')->label(__('crud.ar_name'));
        CRUD::column('en_title')->label(__('crud.en_name'));
        CRUD::column('old_price')->label(__('crud.old_price'));
        CRUD::column('new_price')->label(__('crud.new_price'));

        CRUD::addColumn([
            'name' => 'discount_percent',
            'label' => __('crud.discount_percent'),
            'type' => 'custom_html',
            'value' => function ($entry) {
                return $entry->discount_percent !== null ? $entry->discount_percent . '%' : '<span class="text-muted">—</span>';
            },
        ]);

        CRUD::column('starts_at')->label(__('crud.starts_at'))->type('datetime');
        CRUD::column('ends_at')->label(__('crud.ends_at'))->type('datetime');
        CRUD::column('is_active')->label(__('crud.is_active_label'))->type('boolean');

        CRUD::addColumn([
            'name' => 'is_expired',
            'label' => __('crud.is_expired'),
            'type' => 'custom_html',
            'value' => function ($entry) {
                if ($entry->is_expired) {
                    return '<span class="status-badge status-inactive">' . __('crud.expired') . '</span>';
                }
                return '<span class="status-badge status-active">' . __('crud.running') . '</span>';
            },
        ]);
    }

    protected function setupShowOperation()
    {
        $this->crud->set('show.setFromDb', false);

        CRUD::column('company_id')->label(__('crud.company'));
        CRUD::column('ar_title')->label(__('crud.ar_name'));
        CRUD::column('en_title')->label(__('crud.en_name'));
        CRUD::column('ar_description')->label(__('crud.description'));
        CRUD::column('en_description')->label(__('crud.description'));
        CRUD::column('old_price')->label(__('crud.old_price'));
        CRUD::column('new_price')->label(__('crud.new_price'));
        CRUD::column('starts_at')->label(__('crud.starts_at'))->type('datetime');
        CRUD::column('ends_at')->label(__('crud.ends_at'))->type('datetime');
        CRUD::column('is_active')->label(__('crud.is_active_label'))->type('boolean');
        $this->crud->addColumn([
            'name' => 'image',
            'type' => 'image',
            'width' => '300px',
            'height' => 'auto',
            'label' => __('crud.image'),
            'prefix' => 'storage/',
        ]);
    }

    private function initFilters()
    {
        $this->crud->addFilter([
            'name' => 'company_id',
            'type' => 'select2',
            'label' => __('crud.company'),
        ], function () {
            return Company::pluck('ar_name', 'id')->toArray();
        }, function ($value) {
            $this->crud->addClause('where', 'company_id', $value);
        });

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'inactive',
            'label' => __('crud.inactive_only'),
        ], false, function () {
            $this->crud->addClause('where', 'is_active', false);
        });

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'running',
            'label' => __('crud.running'),
        ], false, function () {
            $this->crud->addClause('running');
        });

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'expired',
            'label' => __('crud.expired'),
        ], false, function () {
            $this->crud->addClause('where', 'ends_at', '<', now());
        });

        $this->crud->addFilter([
            'name' => 'created_range',
            'type' => 'date_range',
            'label' => __('crud.created_at'),
        ], false, function ($value) {
            $dates = json_decode($value);
            if (!empty($dates->from)) {
                $this->crud->addClause('where', 'created_at', '>=', $dates->from);
            }
            if (!empty($dates->to)) {
                $this->crud->addClause('where', 'created_at', '<=', $dates->to . ' 23:59:59');
            }
        });
    }
}
