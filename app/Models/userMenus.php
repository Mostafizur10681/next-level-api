<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class userMenus extends Model
{
    protected $table = 'user_menus';
    protected $primaryKey = 'user_menus_id';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'menu_id',
        'submenus',
        'insert_by',
        'update_by',
    ];
}
