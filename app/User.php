<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Role;
use Empresa;
use Comentario;
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','profile_image','categoria'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role(){
            return $this->belongsTo('App\Role','categoria');
     }
     //relacion de empresa
     public function empresa(){
        return $this->belongsTo('App\Empresa');
       }

       public function messages(){
         return $this->hasMany(Message::class);
     }
     public function tasks(){
       return $this->hasMany(Task::class,'asignador_id');
     }
     public function comentarios(){
       return $this->hasMany(Comentario::class);
     }
}
