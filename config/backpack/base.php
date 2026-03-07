<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Look & feel customizations
    |--------------------------------------------------------------------------
    |
    | Make it yours.
    |
    */

    // Date & Datetime Format Syntax: https://carbon.nesbot.com/docs/#api-localization
    'default_date_format'     => 'D MMM YYYY',
    'default_datetime_format' => 'D MMM YYYY, HH:mm',

    // Direction, according to language
    // (left-to-right vs right-to-left)
    'html_direction' => 'rtl',

    // Project name. Shown in the window title.
    'project_name' => 'Kuwait Explorer Admin Panel',

    // Content of the HTML meta robots tag to prevent indexing and link following
    'meta_robots_content' => 'noindex, nofollow',

    /*
    |--------------------------------------------------------------------------
    | Registration Open
    |--------------------------------------------------------------------------
    |
    | Choose whether new users/admins are allowed to register.
    |
    */

    'registration_open' => env('BACKPACK_REGISTRATION_OPEN', false),

    /*
    |--------------------------------------------------------------------------
    | Routing
    |--------------------------------------------------------------------------
    */

    // The prefix used in all base routes (the 'admin' in admin/dashboard)
    'route_prefix' => 'admin',

    // The web middleware (group) used in all base & CRUD routes
    'web_middleware' => 'web',

    // Set this to false if you would like to use your own AuthController and PasswordController
    'setup_auth_routes' => true,

    // Set this to false if you would like to skip adding the password recovery routes
    'setup_password_recovery_routes' => false,

    // Set this to false if you would like to skip adding the dashboard routes
    'setup_dashboard_routes' => true,

    // Set this to false if you would like to skip adding "my account" routes
    'setup_my_account_routes' => true,

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */

    // Fully qualified namespace of the User model
    'user_model_fqn' => config('auth.providers.users.model'),

    // The classes for the middleware to check if the visitor is an admin
    'middleware_class' => [
        App\Http\Middleware\CheckIfAdmin::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ],

    // Alias for that middleware
    'middleware_key' => 'admin',

    // Username column for authentication
    'authentication_column'      => 'email',
    'authentication_column_name' => 'Email',

    // The guard that protects the Backpack admin panel.
    'guard' => 'backpack',

    // The password reset configuration for Backpack.
    'passwords' => 'backpack',

    // What kind of avatar will you like to show to the user?
    'avatar_type' => 'gravatar',

    /*
    |--------------------------------------------------------------------------
    | Theme (User Interface)
    |--------------------------------------------------------------------------
    */
    'view_namespace' => 'backpack::',

    /*
    |--------------------------------------------------------------------------
    | File System
    |--------------------------------------------------------------------------
    */
    'root_disk_name' => 'root',

    /*
    |--------------------------------------------------------------------------
    | License Code
    |--------------------------------------------------------------------------
    */
    'license_code' => env('BACKPACK_LICENSE', false),
];
