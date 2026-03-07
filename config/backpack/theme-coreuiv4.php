<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Theme Configuration Values
    |--------------------------------------------------------------------------
    |
    | The file provides extra configs on top of config/backpack/ui.php
    |
    | Any value set here will override the ones defined in
    | config/backpack/ui.php when this theme is in use.
    |
    */

    // the layout used for authenticated users in the application
    // this layout is used to display errors to logged users
    'layout' => 'top_left',

    // -------
    // CLASSES
    // -------

    'classes' => [

        'header' => 'header app-header p-0 mb-0 border-0 navbar',

        'body' => 'app aside-menu-fixed sidebar-lg-show',

        'sidebar' => 'sidebar sidebar-fixed sidebar-dark bg-dark-gradient',

        'footer' => 'app-footer d-print-none d-none',

    ],

];
