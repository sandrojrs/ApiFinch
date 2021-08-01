<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'deadline',
        'user_id',
        'status'
    ];
    public function tasks(){               
        return $this->hasMany('App\Models\Task','project_id', 'id');
    }
    public function users(){               
        return $this->hasOne('App\Models\User','id', 'user_id');
    }
    public function start_date_difference_task($date){
        $deadline1= Carbon::parse($this->deadline);
        $deadline2 = Carbon::parse($date);
        return  $deadline1->lt($deadline2);
   }
}
