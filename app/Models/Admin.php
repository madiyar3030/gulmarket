<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $fillable = ['name', 'username', 'password', 'role_id'];

    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
}
