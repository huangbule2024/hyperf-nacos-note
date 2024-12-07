<?php

declare(strict_types=1);

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use Hyperf\ConfigCenter\Mode;
use function Hyperf\Support\env;

$base = [
    'tenant' => 'public',
    'group' => 'DEFAULT_GROUP',
    "type" => 'json',
];
return [
    'enable' => (bool)env('CONFIG_CENTER_ENABLE', true),
    'driver' => env('CONFIG_CENTER_DRIVER', 'nacos'),
    'mode' => env('CONFIG_CENTER_MODE', Mode::PROCESS),
    'drivers' => [
        'nacos' => [
            'driver' => Hyperf\ConfigNacos\NacosDriver::class,
            'merge_mode' => Hyperf\ConfigNacos\Constants::CONFIG_MERGE_APPEND,
            'interval' => 3,
            'default_key' => 'default_key',
            'listener_config' => [
                'geo' => $base + ['data_id' => 'geo'],
                'redis' => $base + ['data_id' => 'redis'],
                'faceserver' => $base + ['data_id' => 'faceserver'],
                'databases' => $base + ['data_id' => 'touchquery-databases.json'],
            ]
        ],
    ],
];
