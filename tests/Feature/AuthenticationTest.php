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

    public function testTaskList(){
        $token = $this->getTokenForUser($this->managerUser());
       
        $baseUrl = Config::get('app.url') . '/api/tasks?token='.$token;
        $response = $this->json('get', $baseUrl, []);
        $response
        ->assertStatus(200)
        ->assertJson([
            'data' => [],
        ]);
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

    // public function testPassword()
    // {
    //     $userData = [
    //         "name" => "executor",
    //         "email" => "executor@hotmail.com",
    //         "cpf" => '47516784001',
    //         'role' => 2
    //     ];

    //     $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json'])
    //         ->assertStatus(422)
    //         ->assertJson([
    //             "message" => "The given data was invalid.",
    //             "errors" => [
    //                 "password" => ["The password confirmation does not match."]
    //             ]
    //         ]);
    // }

    // public function testSuccessfulRegistration()
    // {
    //     $userData = [
    //         "name" => "executor",
    //         "email" => "executor@hotmail.com",
    //         "cpf" => '47516784001',
    //         "password" => "12345678",
    //         'role' => 2        
    //     ];

    //     $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json'])
    //         ->assertStatus(201)
    //         ->assertJsonStructure([
    //             "user" => [
    //                 'id',
    //                 'name',
    //                 'email',
    //                 'cpf',
    //                 'created_at',
    //                 'updated_at',
    //             ],
    //             "access_token",
    //             "message"
    //         ]);
    // }
}
