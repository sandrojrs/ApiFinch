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
        'project_id'
    ];

    public function project(){
        return $this->hasOne('App\Models\Project');
    }
}
