<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    public function testProjectList(){
      $token = $this->getTokenForUser($this->managerUser());
    
      $baseUrl = Config::get('app.url') . '/api/projects?token='.$token;
      $response = $this->json('get', $baseUrl, []);
      $response
      ->assertStatus(200)
      ->assertJson([
          'data' => [],
      ]);
  }
  public function testProjectCreate($headers = []){
      $token = $this->getTokenForUser($this->managerUser());
      $headers = array_merge([
          'Authorization' => 'Bearer '.$token,
      ], $headers);
      $data= [
          'name' => 'teste',          
          'deadline' => Carbon::now()->addDays(3),
        
      ];

      $this->post('/api/projects', $data , $headers)
      ->assertStatus(200);
  }

  public function testProjectUpdate($headers = []){
      $token = $this->getTokenForUser($this->managerUser());
      $headers = array_merge([
          'Authorization' => 'Bearer '.$token,
      ], $headers);
      $data= [
          'name' => 'teste',
          'deadline' => Carbon::now()->addDays(3),
          'status' => 'in_progress'
      ];

      $this->put('/api/projects/1', $data , $headers)
      ->assertStatus(200);
  }

  public function testProjectShow(){
      $token = $this->getTokenForUser($this->managerUser());
    
      $baseUrl = Config::get('app.url') . '/api/projects?id=1&token='.$token;
      $response = $this->json('get', $baseUrl, []);
      $response
      ->assertStatus(200);
  }
}
