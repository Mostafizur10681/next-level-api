<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menus extends Model
{
    protected $table = 'menus';
    protected $primaryKey = 'menu_id';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = true;
    protected $fillable = [
        'menu_name',
        'menu_order_no',
        'base_url',
        'menu_icon',
        'active_yn',
        'insert_by',
        'update_by',
    ];
}
