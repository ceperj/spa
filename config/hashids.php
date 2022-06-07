<?php

/**
 * Copyright (c) Vincent Klaiber.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://github.com/vinkla/laravel-hashids
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Default Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the connections below you wish to use as
    | your default connection for all work. Of course, you may use many
    | connections at once using the manager class.
    |
    */

    'default' => 'main',

    /*
    |--------------------------------------------------------------------------
    | Hashids Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the connections setup for your application. Example
    | configuration has been included, but you may add as many connections as
    | you would like.
    |
    */

    'connections' => [

        'main' => [
            'salt' => 'spa-ceperj',
            'length' => 8,
            // 'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
        ],

        'App\\Models\\User' => [
            'salt' => 'spa::User',
            'length' => 6,
        ],

        'App\\Models\\Project' => [
            'salt' => 'spa::Project',
            'length' => 8,
        ],

        'App\\Models\\Person' => [
            'salt' => 'spa::Person',
            'length' => 8,
        ],

        'App\\Models\\Bank' => [
            'salt' => 'spa::Bank',
            'length' => 8,
        ],

        'App\\Models\\Job' => [
            'salt' => 'spa::Job',
            'length' => 8,
        ],

        'App\\Models\\Battery' => [
            'salt' => 'spa::Battery',
            'length' => 8,
        ],

        'App\\Models\\Irpf' => [
            'salt' => 'spa::Irpf',
            'length' => 8,
        ],

    ],

];