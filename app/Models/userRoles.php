<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = 'user_roles';
    public $incrementing = false;
    protected $primaryKey = null;  
    protected $keyType = 'int';    
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'role_id',
        'insert_by',
        'update_by',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }
}
