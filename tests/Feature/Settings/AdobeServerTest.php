<?php

/* @var \Tests\TestCase $this */

use App\Helpers\PermissionKeys;

uses(App\Traits\FeatureTestTrait::class);

it('can view adobe server setting with permission', function () {
    $response = $this->authorized_user([PermissionKeys::SETTINGS_ADOBE_SERVER_VIEW])
        ->get('/settings/adobe-server');

    $response->assertOk();

});

it('can\'t view adobe server setting without permission', function () {
    $response = $this->unauthorized_user()
        ->get('/settings/adobe-server');

    $response->assertForbidden();
});

it('can store adobe server setting with permission', function () {
    $this->authorized_user([PermissionKeys::SETTINGS_ADOBE_SERVER_UPDATE])
        ->post('/settings/adobe-server',
            [
                'server_address' => 'http://0.0.0.0',
                'username'      => 'user',
                'password'       => 'pass'
            ]
        );

    $this->assertDatabaseHas(
        'settings',
        [
            'component' => 'adobe_server',
            'key' => 'server_address',
            'value' => 'http://0.0.0.0',
        ]
    );
    $this->assertDatabaseHas(
        'settings',
        [
            'component' => 'adobe_server',
            'key' => 'username',
            'value'      => 'user',
        ]
    );
    $this->assertDatabaseHas(
        'settings',
        [
            'component' => 'adobe_server',
            'key' => 'password',
            'value'       => 'pass',
        ]
    );

});

it('can\'t store adobe server setting without permission', function () {
    $response = $this->unauthorized_user()
        ->post('/settings/adobe-server',
            [
                'server_address' => 'http://0.0.0.0',
                'username'      => 'user1',
                'password'       => 'pass1'
            ]
        );
    $response->assertForbidden();

    $this->assertDatabaseMissing(
        'settings',
        [
            'component' => 'adobe_server',
            'key' => 'server_address',
            'value' => 'http://0.0.0.0',
        ]
    );
    $this->assertDatabaseMissing(
        'settings',
        [
            'component' => 'adobe_server',
            'key' => 'username',
            'value'      => 'user1',
        ]
    );
    $this->assertDatabaseMissing(
        'settings',
        [
            'component' => 'adobe_server',
            'key' => 'password',
            'value'       => 'pass1',
        ]
    );

});

it('prevent storing invalid request data', function (?string $address,?string $username,?string $pass) {
    $reponse = $this->authorized_user([PermissionKeys::SETTINGS_ADOBE_SERVER_UPDATE])
        ->post('/settings/adobe-server',
            [
                'server_address' => $address,
                'username'      => $username,
                'password'       => $pass
            ]
        );

    $reponse->assertInvalid();

})->with([
    ['http://0.0.0.0',null,null],
    [null,'user','pass'],
    ['http://0.0.0.0',null,'pass'],
]);

it('validate request data', function (string $address,string $username,string $pass,) {
    $reponse = $this->authorized_user([PermissionKeys::SETTINGS_ADOBE_SERVER_UPDATE])
        ->post('/settings/adobe-server',
            [
                'server_address' => $address,
                'username'      => $username,
                'password'       => $pass
            ]
        );

    $reponse->assertValid();

})->with(
    [['http://0.0.0.0','user','pass'],
    ['http://adobe.server.com','user@meial.com','pass2A#%s'],
    ['http://192.168.0.1/api','user','passsas'],]
);
