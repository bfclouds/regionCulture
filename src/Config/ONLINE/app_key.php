<?php
/**
 * User: coderd
 * Date: 2018/8/21
 * Time: 17:45
 */

use App\Config\ApiName;

return [
    'app_keys' => [
        'test' => '1',
    ],
    'permissions' => [
        'test' => [
            ApiName::PASSPORT_GET_USER
        ]
    ]
];