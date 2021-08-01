<?php

namespace Tests;

use App\Models\Role;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    const AUTH_PASSWORD = 'password';

    public function actingAs($user, $driver = null)
    {
        $token = JWTAuth::fromUser($user);
        $this->withHeader('Authorization', "Bearer {$token}");
        parent::actingAs($user);
        
        return $this;
    }

    public function getTokenForUser(User $user) : string
    {
        return JWTAuth::fromUser($user);
    }

    public function ManagerUser() : User
    {
        $user = User::find(1);

        if ($user) {
            return $user;
        }
    }
}
