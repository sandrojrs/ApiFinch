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

    // public function testResgister(){
    //     $token = $this->getTokenForUser($this->managerUser());
    //     $data = [
    //         'email' => 'test@gmail.com',
    //         'name' => 'Test',
    //         'cpf' => '18243352023',
    //         'password' => '12345678',
    //         'role' => 2
    //     ];
    //     // Send a post request
    //     $response = $this->json('POST', 'api/register' , $data, ['Authorization' => "Bearer $token", 
    //     'Accept' => 'application/json']);
    //     // Determine whether the transmission was successful
    //     $response->assertStatus(200);
    //     $this->assertArrayHasKey('token',$response->json()); 
    //     // Receive our token
    //     // $this->assertArrayHasKey('token',$response->json());
    //     // Delete data
    //     // User::where('email','test@gmail.com')->delete();
    // }
}
