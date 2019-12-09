<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class TaskUser extends Model
{
    protected $table = "task_user";

    protected $fillable = [
    'task_id', 'user_id'
    ];

    public function Task(){

        return $this->belongsTo(Task::class);
    }
    public function User(){

        return $this->belongsTo(User::class);
    }
}
