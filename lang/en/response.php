<?php

return [
    'error'  => 'An internal error has occurred.',
    'models' => [
        'user'              => 'User',
        'role'              => 'Role',
        'permission'        => 'Role Permissions',
    ],
    'delete' => [
        'failed'       => 'Error deleting :model',
        'has_relation' => 'Cannot delete :model due to related data.',
        'success'      => ':model deleted successfully.',
    ],
    'csv' => [
        'error' => 'Error creating Excel file.'
    ],
    'create' => [
        'failed'  => 'Error creating :model',
        'success' => ':model created successfully.',
    ],
    'update' => [
        'failed'  => 'Error updating :model',
        'success' => ':model updated successfully.',
    ],
    'user' => [
        'delete' => [
            'failed'       => 'Error deleting the user',
            'has_relation' => 'Cannot delete the user due to related data.',
            'success'      => 'User deleted successfully.',
        ],
        'restore' => [
            'success' => 'User restored successfully.'
        ],
        'profile' => [
            'update' => [
                'success' => 'Your profile has been successfully updated.'
            ]
        ]
    ],
];
