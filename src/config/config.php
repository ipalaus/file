<?php

return [

    'model' => 'Ipalaus\File\Repository\Eloquent',

    'storage' => [

        /*
        |--------------------------------------------------------------------------
        | Default Storage Driver
        |--------------------------------------------------------------------------
        |
        | Here you may specify which of the storage drivers below you wish
        | to use as your default storage for all the created files.
        |
        */

        'default' => 'local',

        /*
        |--------------------------------------------------------------------------
        | Storage Drivers
        |--------------------------------------------------------------------------
        |
        | Here you may specify which of the database connections below you wish
        | to use as your default connection for all database work. Of course
        | you may use many connections at once using the Database library.
        |
        */

        'drivers' => [

            'local' => [
                'root' => storage_path('app/files'),
            ],

        ],
    ],

];
