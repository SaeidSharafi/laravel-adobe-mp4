<?php

/* @var \Tests\TestCase $this */

use App\Helpers\PermissionKeys;

uses(App\Traits\FeatureTestTrait::class);

it('can view adobe server setting with permission', function () {
    $response = $this->unauthorized_user()
        ->get('/setting/adobe-server');

    $response->assertOk();

});

it('can\'t view adobe server setting without permission', function () {
    $response = $this->authorized_user([PermissionKeys::SETTINGS_ADOBE_SERVER_VIEW])
        ->get('/setting/adobe-server');

    $response->assertForbidden();
});

it('can store adobe server setting with permission', function () {
    $this->authorized_user([PermissionKeys::SETTINGS_ADOBE_SERVER_CREATE])
        ->post('/setting/adobe-server',
            [
                'server_address' => '0.0.0.0',
                'user_name'      => 'user',
                'password'       => 'pass'
            ]
        );

    $this->assertDatabaseHas(
        'settings',
        [
            'adobe_server.server_address' => '0.0.0.0',
            'adobe_server.user_name'      => 'user',
            'adobe_server.password'       => 'pass',
        ]
    );

});

it('can\'t store adobe server setting without permission', function () {
    $this->unauthorized_user()
        ->post('/setting/adobe-server',
            [
                'server_address' => '0.0.0.0',
                'user_name'      => 'user1',
                'password'       => 'pass1'
            ]
        );

    $this->assertDatabaseMissing(
        'settings',
        [
            'adobe_server.server_address' => '0.0.0.0',
            'adobe_server.user_name'      => 'user1',
            'adobe_server.password'       => 'pass1',
        ]
    );

});

it('prevent storing invalid request data', function (string $address,string $username,string $pass,) {
    $reponse = $this->authorized_user([PermissionKeys::SETTINGS_ADOBE_SERVER_CREATE])
        ->post('/setting/adobe-server',
            [
                'server_address' => $address,
                'user_name'      => $username,
                'password'       => $pass
            ]
        );

    $reponse->assertInvalid();

})->with(
    ['0.0.0.0',null,null],
    [null,'user','pass'],
    ['0.0.0.0',null,'pass'],
);

it('validate request data', function (string $address,string $username,string $pass,) {
    $reponse = $this->authorized_user([PermissionKeys::SETTINGS_ADOBE_SERVER_CREATE])
        ->post('/setting/adobe-server',
            [
                'server_address' => $address,
                'user_name'      => $username,
                'password'       => $pass
            ]
        );

    $reponse->assertValid();

})->with(
    ['0.0.0.0','user','pass'],
    ['adobe.server.com','user@meial.com','pass2A#%s'],
    ['192.168.0.1/api','user','passsas'],
);
