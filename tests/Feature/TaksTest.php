<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;


class TaksTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testTaskList(){
        $token = $this->getTokenForUser($this->managerUser());
       
        $baseUrl = Config::get('app.url') . '/api/tasks?token='.$token;
        $response = $this->json('get', $baseUrl, []);
        $response
        ->assertStatus(200);
    }
    public function testTaskCreate($headers = []){
        $token = $this->getTokenForUser($this->managerUser());
        $headers = array_merge([
            'Authorization' => 'Bearer '.$token,
        ], $headers);
        $data= [
            'title' => 'teste',
            'description' => 'teste' ,
            'deadline' => Carbon::now()->addDays(1),
            'project_id' => 1,
            'status' => 'done'
        ];
    
        $this->post('/api/tasks', $data , $headers)
        ->assertStatus(200);
    }
    
    public function testTaskUpdate($headers = []){
        $token = $this->getTokenForUser($this->managerUser());
        $headers = array_merge([
            'Authorization' => 'Bearer '.$token,
        ], $headers);
        $data= [
            'title' => 'teste',
            'description' => 'teste' ,
            'deadline' => Carbon::now()->addDays(1),
            'project_id' => 1,
            'status' => 'done'
        ];
    
        $this->put('/api/tasks/1', $data , $headers)
        ->assertStatus(200);
    }

    public function testTaskShow(){
        $token = $this->getTokenForUser($this->managerUser());
       
        $baseUrl = Config::get('app.url') . '/api/tasks?id=1&token='.$token;
        $response = $this->json('get', $baseUrl, []);
        $response
        ->assertStatus(200);
    }
}
