<?php

use App\Models\Auth\User;
use App\Services\Auth\OtpService;
use Illuminate\Support\Facades\Config;
use Inertia\Testing\AssertableInertia;

it('test login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

it('test users get redirecetd to password screen with phone email', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'username' => $user->email,
    ]);

    $response->assertRedirect(route('password', ['username' => $user->email]));
});

it('test users get redirecetd to password screen with phone number', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'username' => $user->phone,
    ]);

    $response->assertRedirect(route('password', ['username' => $user->phone]));
});

it('test users get redirecetd to otp screen with phone number when password is not set', function () {
    $user = User::factory()->create(
        ['password' => null]
    );

    $this->followingRedirects()
        ->post('/login', [
            'username' => $user->phone,
        ])
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('useOTP')
            ->where('username', $user->phone)
            ->where('useOTP', true));

    $this->assertDatabaseHas('otp', [
        'user_id' => $user->id,
        'type'    => 'verify'
    ]);
});

it('test users get redirecetd to otp screen with email when password is not set', function () {
    $user = User::factory()->create(
        ['password' => null]
    );

    $this
        ->followingRedirects()
        ->post('/login', [
            'username' => $user->email,
        ])
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('useOTP')
            ->where('username', $user->email)
            ->where('useOTP', true));

    $this->assertDatabaseHas('otp', [
        'user_id' => $user->id,
        'type'    => 'verify'
    ]);
});

it('test users can not authenticate with invalid username', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'username' => "test",
    ])->assertSessionHasErrors();
});

test('user con not register if allow_registration is false', function () {
    $this->post('/login', [
        'username' => "09351256548",
    ])->assertSessionHasErrors();
});

it('test user created in case it does not exist', function () {
    Config::set('auth.allow_registration', true);
    $this->post('/login', [
        'username' => "09351256548",
    ])->assertRedirect(route('password', ['username' => '09351256548']));
    $this->assertDatabaseHas('users', [
        'phone' => "09351256548",
    ]);
});

it('test newly created user can login with otp', function () {
    Config::set('auth.allow_registration', true);
    $this->followingRedirects()
        ->post('/login', [
            'username' => "09351256548",
        ])
        ->assertSessionHasNoErrors()
        ->assertOk();

    $this->assertDatabaseHas('users', [
        'phone' => "09351256548",
    ]);

    $token = OtpService::getOTPWithUserPhone('09351256548')->token;

    $this->post("/password", [
        'username' => '09351256548',
        'otp'      => $token,
    ])->assertSessionHasNoErrors();

    $this->assertAuthenticated();
});

it('test users can authenticate with correct email and password', function () {
    $user = User::factory()->create();

    $this->post("/password?username={$user->email}", [
        'password' => "password",
    ]);

    $this->assertAuthenticated();
});

it('test users can authenticate with correct phone number and password', function () {
    $user = User::factory()->create();

    $this->post("/password?username={$user->phone}", [
        'password' => "password",
    ]);

    $this->assertAuthenticated();
});

it('test users can authenticate with correct email and otp', function () {
    $user = User::factory()->create(
        ['password' => null]
    );
    $this->followingRedirects()
        ->post('/login', [
            'username' => $user->email,
        ])->assertOk();

    $token = OtpService::getOTP($user)->token;

    $this->post("/password", [
        'username' => $user->email,
        'otp'      => $token,
    ])->assertSessionHasNoErrors();

    $this->assertAuthenticated();
    $this->assertDatabaseMissing('otp', [
        'user_id' => $user->id,
        'type'    => 'verify',
    ]);
});

it('test users can authenticate with correct phone number and otp', function () {
    $user = User::factory()->create(
        ['password' => null]
    );

    $this->followingRedirects()
        ->post('/login', [
            'username' => $user->phone,
        ])->assertOk();

    $token = OtpService::getOTP($user)->token;

    $this->post("/password", [
        'username' => $user->phone,
        'otp'      => $token,
    ])->assertSessionHasNoErrors();

    $this->assertAuthenticated();
    $this->assertDatabaseMissing('otp', [
        'user_id' => $user->id,
        'type'    => 'verify',
    ]);
});

it('test users can authenticate with correct email wrong otp', function () {
    $user = User::factory()->create(
        ['password' => null]
    );
    $token = OtpService::generateOTP($user)->token;
    $token++;

    $this->post("/password", [
        'username' => $user->email,
        'otp'      => $token,
    ]);

    $this->assertGuest();
});

it('test users can authenticate with correct phone wrong otp', function () {
    $user = User::factory()->create(
        ['password' => null]
    );
    $token = OtpService::generateOTP($user)->token;
    $token++;

    $this->post("/password", [
        'username' => $user->phone,
        'otp'      => $token,
    ]);

    $this->assertGuest();
});

it('test users can not authenticate with correct email wrong password', function () {
    $user = User::factory()->create();

    $this->post("/password", [
        'username' => $user->email,
        'password' => "123456",
    ]);

    $this->assertGuest();
});

it('test users can not authenticate with correct phone wrong password', function () {
    $user = User::factory()->create();

    $this->post("/password", [
        'username' => $user->phone,
        'password' => "123456",
    ]);

    $this->assertGuest();
});
