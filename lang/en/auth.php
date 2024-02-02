<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'forgot_password_content'     => 'Pleas enter your mobile phone number to recive verification code',
    'forgot_password_otp_content' => 'Pleas enter verification code',
    'failed'                      => 'These credentials do not match our records.',
    'otp_failed'                  => 'OTP code do not match our records.',
    'otp_expired'                 => 'OTP code expired.',
    'password'                    => 'The provided password is incorrect.',
    'throttle'                    => 'Too many login attempts. Please try again in :seconds seconds.',
    'log_in'                      => 'Log in',
    'log_out'                     => 'Log Out',
    'remember_me'                 => 'Remember me',
    'forgot_password'             => 'Forgot your password?',
    'reset_password'              => 'Reset password',

    'user' => [
        'title' => [
            'self'   => 'User',
            'index'  => 'Users',
            'create' => 'Create User',
            'edit'   => 'Edit User',
        ],
        'email_phone'           => 'Email/Phone',
        'otp'                   => 'One-Time Password',
        'profile'               => 'Profile',
        'code'                  => 'Code',
        'first_name'            => 'First Name',
        'last_name'             => 'Last Name',
        'email'                 => 'Email',
        'phone'                 => 'Phone',
        'current_password'      => 'Current Password',
        'password'              => 'Password',
        'password_confirmation' => 'Password Confirmation',
        ''                      => '',
    ],
    'role' => [
        'title' => [
            'self'   => 'Role',
            'index'  => 'Roles',
            'create' => 'Create Role',
            'edit'   => 'Edit Role',
        ],
        'name'      => 'Name',
        'label'     => 'Label',
        'can_anage' => 'Can Manage',
    ],
    'permission' => [
        'title' => [
            'self'  => 'Permission',
            'index' => 'Permissions'
        ],
        'dashboard'     => 'میز کار',
        'user'          => 'کاربران',
        'role'          => 'نقش ها',
        'report'        => "گزارشات",
        'activity'      => "فعالیت‌ها",
        'log'           => "لاگ ها",
        'view'          => "مشاهده",
        'view_own'      => "مشاهده خود",
        'create'        => "ایجاد",
        'update'        => "ویرایش",
        'update_own'    => "ویرایش خود",
        'delete'        => "حذف",
        'delete_own'    => "حذف خود",
    ]

];
