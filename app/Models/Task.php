<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        return $this->hasOne('App\Models\Project', 'id', 'project_id');
    }
    public function user(){
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
