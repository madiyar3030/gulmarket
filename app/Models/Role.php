<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //SELECT count(three.id), three.referBy FROM three JOIN users on users.id = three.user_id WHERE users.bot = 1 GROUP BY three.referBy
    const ACCESS_READ = 1;
    const ACCESS_CREATE = 2;
    const ACCESS_UPDATE = 4;
    const ACCESS_DELETE = 8;

    protected $fillable = ['title', 'orders', 'users', 'chats', 'categories', 'items', 'general', 'admin', 'roles', 'lists'];
}
