<?php

return [
    'user' => [
        'model' => 'App\User',
    ],
    'broadcast' => [
        'enable' => true,
        'app_name' => 'mawared-app',
        'pusher' => [
            'app_id' => '1074547',
            'app_key' => '6fb78eff4fbbe0cd5095',
            'app_secret' => '4d336ae8d5b4018d3515',
            'options' => [
                'cluster' => 'eu',
                'encrypted' => true,

            ]
        ],
    ],

    'oembed' => [
        'enabled' => false,
        'url' => '',
        'key' => ''
    ]
];
