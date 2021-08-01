<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Config;

class UserTest extends TestCase
{
    public function testUserList(){
      $token = $this->getTokenForUser($this->managerUser());
      $baseUrl = Config::get('app.url') . '/api/users?token='.$token;
      $response = $this->json('get', $baseUrl, []);
      $response
      ->assertStatus(200);
  }
  public function testUserCreate($headers = []){
    $faker = \Faker\Factory::create('pt_BR');
      $token = $this->getTokenForUser($this->managerUser());
      $headers = array_merge([
          'Authorization' => 'Bearer '.$token,
      ], $headers);
      $data= [
        'name' => $faker->name,
        'cpf' => $faker->cpf ,
        'email' => $faker->email,
        'password' => '12345678',
        'roles' => 1
    ];

      $this->post('/api/users', $data , $headers)
      ->assertStatus(200);
  }

  public function testUserUpdate($headers = []){
      $faker = \Faker\Factory::create('pt_BR');
      $token = $this->getTokenForUser($this->managerUser());
      $headers = array_merge([
          'Authorization' => 'Bearer '.$token,
      ], $headers);
      $data= [
          'name' => $faker->name,
          'cpf' => $faker->cpf ,
          'email' => $faker->email,
          'password' => '12345678',
          'roles' => 1
      ];

      $this->put('/api/users/2', $data , $headers)
      ->assertStatus(200);
  }

  public function testUserShow(){
      $token = $this->getTokenForUser($this->managerUser());
    
      $baseUrl = Config::get('app.url') . '/api/users?id=1&token='.$token;
      $response = $this->json('get', $baseUrl, []);
      $response
      ->assertStatus(200);
  }
}
