<?php

return [

    /*
    |--------------------------------------------------------------------------
    | File Model
    |--------------------------------------------------------------------------
    |
    | Eloquent model used to store and retrieve the files.
    |
    */

    'model' => [
        'file'           => 'Ipalaus\File\Repositories\FileEloquent',
        'transformation' => 'Ipalaus\File\Repositories\TransformationEloquent',
    ],

    /*
    |--------------------------------------------------------------------------
    | Storage engines
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the storage engines below you wish to use
    | as your default storage for all the created files.
    |
    */

    'storage' => [

        'default' => 'local',

        'drivers' => [

            'local' => [
                'root' => storage_path('app/files'),
            ],

        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Transformers
    |--------------------------------------------------------------------------
    |
    | TBD
    |
    */

    'transformers' => [
        //
    ],

];
