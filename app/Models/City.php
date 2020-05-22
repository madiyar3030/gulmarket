<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $hidden = ['updated_at', 'created_at'];

    protected $fillable = ['city'];
}
