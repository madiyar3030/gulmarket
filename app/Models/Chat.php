<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $hidden = ['user_id', 'updated_at'];

    protected $fillable = ['user_id', 'message', 'destination', 'status'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
