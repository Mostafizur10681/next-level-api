<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'role_id';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = true;
    protected $fillable = [
        'role_name',
        'role_key',
        'grant_all_yn',
        'active_yn',
        'insert_by',
        'update_by',
    ];
}
