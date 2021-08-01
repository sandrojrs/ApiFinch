<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Config;

class AuthenticationTest  extends TestCase
{


    public function testAuth()
    {
        $response = $this->json('POST','api/login',[
            'email' => 'manager@hotmail.com',
            'password' => '12345678',
        ]);
        // Determine whether the login is successful and receive token 
        $response->assertStatus(200);
        $this->assertArrayHasKey('token',$response->json());        
       
    } 

    public function testlogout()
    {
        $token = $this->getTokenForUser($this->managerUser());
        
        $baseUrl = Config::get('app.url') . '/api/logout?&token='.$token;
        $response = $this->json('get', $baseUrl, []);
        $response
        ->assertStatus(200);  
       
    } 
}
