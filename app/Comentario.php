<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Task;
use App\User;
use App\Empresa;

class Comentario extends Model
{
    public function task(){
      return $this->belongsTo(Task::class);
    }
    public function user(){
      return $this->belongsTo(User::class);
    }
    public function empresa(){
      return $this->belongsTo(Empresa::class);
    }
}
