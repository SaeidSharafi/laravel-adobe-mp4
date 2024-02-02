<?php

return [
    'dashboard' => [
        [
            'key'         => 'admin',
            'title'       => 'auth.permission.dashboard.admin',
            'hasOwn'      => false,
            'viewOnly'    => true,
            'permissions' => []

        ],
    ],


    'users' => [
        [
            'key'         => 'user',
            'title'       => 'auth.permission.user',
            'hasOwn'      => true,
            'viewOnly'    => false,
            'permissions' => []
        ],

        [
            'key'         => 'role',
            'title'       => 'auth.permission.role',
            'hasOwn'      => false,
            'viewOnly'    => false,
            'permissions' => []
        ],
    ],

    'reports' => [
        [
            'key'         => 'report',
            'title'       => 'auth.permission.report',
            'hasOwn'      => false,
            'viewOnly'    => false,
            'permissions' => []
        ],
        [
            'key'         => 'activity',
            'title'       => 'auth.permission.activity',
            'hasOwn'      => false,
            'viewOnly'    => false,
            'permissions' => []
        ],
        [
            'key'         => 'log',
            'title'       => 'auth.permission.log',
            'hasOwn'      => false,
            'viewOnly'    => false,
            'permissions' => []
        ],
    ],
];
