<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Comentario;
use App\TaskUser;

class Task extends Model
{
    public function users(){
      return $this->hasMany(TaskUser::class);
    }
    public function empresa(){
      return $this->belongsTo(Empresa::class);
    }
    public function comentarios(){
      return $this->hasMany(Comentario::class);
    }
    public function user(){
      return $this->belongsTo(User::class,'asignador_id');
    }
}
