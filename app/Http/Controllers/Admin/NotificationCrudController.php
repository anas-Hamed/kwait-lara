<?php

namespace App\Http\Controllers\Admin;

use App\Models\ExtendedDatabaseNotification;
use App\Models\User;
use App\Notifications\AdminCustomNotificationForUser;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Notification;
use Prologue\Alerts\Facades\Alert;

/**
 * Class TagCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class NotificationCrudController extends CrudController
{
    use CreateOperation{
        store as _store;
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(ExtendedDatabaseNotification::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/notification');
        CRUD::setEntityNameStrings('إشعار', 'الإشعارات');
    }
    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->data['breadcrumbs'] = [ ];
        CRUD::field('title')->label('العنوان');
        CRUD::field('body')->label('نص الإشعار')->type('textarea');
    }

    public function store()
    {
        $request = $this->crud->getRequest();
        $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);
        $users = User::query()->where('is_active',true)->where('is_admin',false)->get();
        Notification::send($users,new AdminCustomNotificationForUser($request->title,$request->body));
        Alert::success('تمت العملية')->flash();
        return back();
    }
}
