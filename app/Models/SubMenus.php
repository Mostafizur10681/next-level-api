<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubMenus extends Model
{
    protected $table = 'sub_menus';
    protected $primaryKey = 'sub_menu_id';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = true;
    protected $fillable = [
        'sub_menu_name',
        'menu_id',
        'base_url',
        'menu_icon',
        'menu_order_no',
        'active_yn',
        'insert_by',
        'update_by',
    ];
}
