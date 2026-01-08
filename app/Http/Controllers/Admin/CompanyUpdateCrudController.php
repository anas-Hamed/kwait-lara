<?php

namespace App\Http\Controllers\Admin;

use App\Models\CompanyUpdate;
use App\Notifications\CompanyUpdatesApprovedNotificationForUser;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Prologue\Alerts\Facades\Alert;

/**
 * Class CompanyUpdateCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CompanyUpdateCrudController extends CrudController
{
    use ListOperation;
    use ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\CompanyUpdate::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/company-update');
        CRUD::setEntityNameStrings('تحديثات الشركات', 'تحديثات الشركات');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumn([
            'name' => 'company_id',
            'type' => 'select',
            'label' => 'الشركة',
            'attribute' => 'ar_name'
        ]);

    }
    protected function setupShowOperation()
    {
        $this->crud->addColumn([
            'name' => 'company_id',
            'type' => 'select',
            'label' => 'الشركة',
            'attribute' => 'ar_name'
        ]);
        $this->crud->addColumn([
            'name' => 'new_values',
            'type' => 'values_changes',
            'label' => 'التغييرات'
        ]);

        $this->crud->addButtonFromView('line','approveUpdates','approveUpdates');

    }

    public function approveUpdates(CompanyUpdate $update){
        DB::beginTransaction();
        try{
            $update->company()->update((array)$update->new_values);
            $update->delete();

            DB::commit();
            Alert::success('تم قبول التعديلات')->flash();
            Notification::send($update->company->user,new CompanyUpdatesApprovedNotificationForUser($update->company));
            return redirect($this->crud->route);
        }catch (\Throwable $exception){
            DB::rollBack();
            Alert::error('فشل قبول التعديلات')->flash();
            return redirect($this->crud->route);
        }
    }

}
