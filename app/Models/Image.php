<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $hidden = ['id', 'created_at', 'updated_at', 'pivot'];

    protected $fillable = ['path'];

    public function getPathAttribute($value)
    {
        if ($value) {
            return asset($value);
        } else {
            return asset('images/no-image.png');
        }
    }
}
