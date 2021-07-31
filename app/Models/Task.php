<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'deadline',
        'user_id',
        'project_id',
        'status'
    ];

    public function project(){
        return $this->hasOne('App\Models\Project');
    }
}
