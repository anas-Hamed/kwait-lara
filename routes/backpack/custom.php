<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array)config('backpack.base.web_middleware', 'web'),
        (array)config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::get('dashboard', "DashboardController@index");
    Route::get('company/{id}/confirm-paid', 'CompanyCrudController@confirmPaid');
    Route::crud('user', 'UserCrudController');
    Route::crud('admin', 'AdminCrudController');
    Route::crud('category', 'CategoryCrudController');
    Route::crud('company', 'CompanyCrudController');
    Route::crud('ad', 'AdCrudController');

    Route::crud('company-update', 'CompanyUpdateCrudController');
    Route::get('company-update/{update}/approve-updates', 'CompanyUpdateCrudController@approveUpdates');

    //    Notifications Crud
    Route::crud('notification', 'NotificationCrudController');


//    Notifications Stuff
    Route::get('notifications', "NotificationController@indexAjax");
    Route::get('allnotifications', "NotificationController@index");
    Route::get('read_all_notifications', "NotificationController@makeAllAsRead");
    Route::get('read_notification/{notification}', "NotificationController@makeAsRead");
    Route::get('notification/{notification}', "NotificationController@redirectToNotification");


//    Charts Routes
    Route::get('charts/category-companies', 'Charts\CompaniesByCategoryChartController@response')->name('charts.category-companies.index');


//    Contact Us
    Route::get('contactus/{id}/read', 'ContactUsCrudController@readMessage');
    Route::crud('contactus', 'ContactUsCrudController');
    Route::crud('blog', 'BlogCrudController');
    Route::crud('company-trust-request', 'CompanyTrustRequestCrudController');
    Route::get('company-trust-request/{companyTrustRequest}/trust', 'CompanyTrustRequestCrudController@trustCompany');
    Route::crud('deleted-company', 'DeletedCompanyCrudController');
}); // this should be the absolute last line of this file