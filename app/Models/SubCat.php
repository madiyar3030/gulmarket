<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCat extends Model
{
    protected $hidden = ['updated_at', 'created_at', 'hidden', 'cat_id'];

    protected $fillable = ['cat_id', 'hidden', 'title', 'thumb'];

    public function parents()
    {
        return $this->belongsTo(Cat::class);
    }
    public function items()
    {
        return $this->hasMany(Item::class);
    }
    public static function getAll()
    {
        return self::with('parents')
                    ->get();
    }
}
