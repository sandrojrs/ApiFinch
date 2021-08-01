<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    
    public function toArray($request)
    {

        return [
            "id"=>  $this->id,
            "title"=>  $this->title,
            "description"=>  $this->description,
            "deadline"=>  $this->deadline,
            "status"=>  $this->status,            
            "user" => $this->user,
            "project"=>  $this->project
        ];
    }
}
