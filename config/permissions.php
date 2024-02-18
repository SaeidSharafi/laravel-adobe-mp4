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

    'users'    => [
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
    'adobe-connect' => [
        [
            'key'         => 'recording',
            'title'       => 'auth.permission.adobe-connect.recording',
            'hasOwn'      => false,
            'viewOnly'    => false,
            'permissions' => []
        ]
    ],
    'settings' => [
        [
            'key'         => 'adobe-server',
            'title'       => 'auth.permission.setting.adobe-server',
            'hasOwn'      => false,
            'viewOnly'    => false,
            'only'        => ['view', 'update'],
            'permissions' => []
        ]
    ],
    'reports'  => [
        [
            'key'         => 'report',
            'title'       => 'auth.permission.report',
            'hasOwn'      => false,
            'viewOnly'    => false,
            'only' => [],
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
