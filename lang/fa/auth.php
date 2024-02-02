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

    'forgot_password_content'     => 'لطفا شماره موبایل خود را جهت ارسال کد تایید وارد کنید.',
    'forgot_password_otp_content' => 'لطفا کد تایید دریافت شده را وارد نمایید.',
    'failed'                      => 'کاربری با اطلاعات وارد شده یافت نشد.',
    'otp_failed'                  => 'کد وارد شده اشتباه است',
    'otp_expired'                 => 'کد وارد شده منقضی شده است.',
    'password_fail'               => 'رمز عبور اشتباه است.',
    'throttle'                    => 'تعداد درخواست های ارسال شده بیش از حد مجاز میباشد. بطفا بعد از :seconds ثانیه مجددا تلاش نمایید.',
    'log_in'                      => 'ورود | ثبت نام',
    'password'                    => 'رمز عبور',
    'log_out'                     => 'خروج',
    'remember_me'                 => 'مرا به خاطر بسپار',
    'forgot_password'             => 'فراموشی رمز عبور',
    'reset_password'              => 'بازنشانی رمز عبور',
    'user'                        => [
        'title'                 => [
            'self'   => 'کاربر',
            'index'  => 'کاربران',
            'create' => 'ایجاد کاربر',
            'edit'   => 'ویرایش کاربر',
        ],
        'email_phone'           => 'موبایل/ایمیل',
        'otp'                   => 'کد تایید',
        'profile'               => 'پروفایل',
        'code'                  => 'کد',
        'first_name'            => 'نام',
        'last_name'             => 'نام خانوادگی',
        'email'                 => 'ایمیل',
        'phone'                 => 'موبایل',
        'current_password'      => 'رمز عبور فعلی',
        'password'              => 'رمز عبور جدید',
        'password_confirmation' => 'تایید رمز عبور',
    ],
    'role'                        => [
        'title'     => [
            'self'   => 'نقش',
            'index'  => 'نقش ها',
            'create' => 'ایجاد نقش',
            'edit'   => 'ویرایش نقش',
        ],
        'name'      => 'عنوان (خاص)',
        'label'     => 'نام نمایشی',
        'can_anage' => 'الویت بر نقش های',
    ],
    'permission'                  => [
        'title'                => [
            'self'  => 'مجوز',
            'index' => 'مجوزها'
        ],
        'dashboard'            => [
            'admin'       => 'میز کار مدیریت',
            'infertility' => 'میزکار ناباروروی',
        ],
        'user'                 => 'کاربران',
        'role'                 => 'نقش ها',
        'report'               => "گزارشات",
        'activity'             => "فعالیت‌ها",
        'log'                  => "لاگ ها",
        'view'                 => "مشاهده",
        'view_own'             => "مشاهده خود",
        'create'               => "ایجاد",
        'update'               => "ویرایش",
        'update_own'           => "ویرایش خود",
        'delete'               => "حذف",
        'delete_own'           => "حذف خود",
    ]
];
