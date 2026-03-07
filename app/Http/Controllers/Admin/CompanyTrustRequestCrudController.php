<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use App\Models\CompanyTrustRequest;
use App\Notifications\CompanyTrustedNotificationForUser;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Prologue\Alerts\Facades\Alert;

/**
 * Class CompanyTrustRequestCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CompanyTrustRequestCrudController extends CrudController
{
    use ListOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(CompanyTrustRequest::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/company-trust-request');
        CRUD::setEntityNameStrings(__('crud.trust_request'), __('crud.trust_requests'));
    }

    protected function setupListOperation()
    {
        $this->crud->addColumn([
            'name' => 'company_id',
            'label' => __('crud.company'),
            'type' => 'name_link',
            'entity' => 'company',
            'prefix' => 'company',
            'attribute' => 'ar_name',
        ]);
        $this->crud->addColumn([
            'name' => 'user_id',
            'label' => __('crud.user_name'),
            'type' => 'name_link',
            'entity' => 'user',
            'prefix' => 'user',
            'attribute' => 'name',
        ]);
        $this->crud->addColumn([
            'name' => 'created_at',
            'label' => __('crud.request_date'),
            'type' => 'datetime',
        ]);

        $this->crud->addButtonFromModelFunction('line', 'trustCompany', 'trustCompany');
    }


    public function trustCompany(CompanyTrustRequest $companyTrustRequest)
    {
        DB::beginTransaction();
        try {

            $company = $companyTrustRequest->company;
            $company->is_trusted = true;
            $company->save();
            $companyTrustRequest->delete();
            Notification::send($companyTrustRequest->user, new CompanyTrustedNotificationForUser($company));
            Alert::success(__('crud.trust_verified'))->flash();
            DB::commit();
            return back();
        } catch (\Throwable $throwable) {
            DB::rollBack();
            Alert::error(__('crud.trust_failed'))->flash();

            return back();
        }
    }

}
