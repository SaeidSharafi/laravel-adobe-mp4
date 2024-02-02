<?php

return [
    'title'   => 'Laravel Easy Dash',
    'version' => 'Version :version',
    'home'    => 'Home',
    'nav'     => [
        'top' => [
            'my_profile' => 'My Profile',
            'settings'   => 'Settings',
            'messages'   => 'Messages',
            'light_dark' => 'Light/Dark',
        ],
        'dashboard_view' => [
            'title'   => 'Dashboard View',
            'manager' => 'Manager',
        ],
        'side' => [
            'dashboard' => 'Dashboard',
            'users'     => 'Users',
            'roles'     => 'Roles',
            'reports'   => [
                'parent' => 'Reprots',
                'logs'   => 'Activity',
            ],

        ],
    ],
    'format' => [
        'date_short'      => 'YYYY-MM-DD',
        'date_long'       => 'dddd DD MMM YYYY',
        'date_time_short' => 'YY-MM-DD HH!:mm',
        'date_time_long'  => 'dddd DD MMM YYYY HH!:mm',
        'time'            => 'HH!:mm',
    ],
    'week_day' => [
        'sat' => 'Sat',
        'sun' => 'Sun',
        'mon' => 'Mon',
        'tue' => 'Tue',
        'wen' => 'Wen',
        'thu' => 'Thu',
        'fri' => 'Fri',
    ],
    'data_table' => [
        'active_filter'    => ':number Active filters',
        'pagination'       => 'Pagination',
        'next'             => 'Next',
        'no_results_found' => 'No results found',
        'of'               => 'of',
        'per_page'         => 'per page',
        'previous'         => 'Previous',
        'results'          => 'results',
        'to'               => 'to',
    ],
    'calendar' => [
        'today' => 'today',
        'month' => 'month',
        'week'  => 'week',
        'daily' => 'daily',
        'day'   => 'day',
        'list'  => 'list',
    ],

];
