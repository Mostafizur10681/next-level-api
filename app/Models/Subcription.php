<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcription extends Model
{
    protected $table = 'subscription';
    protected $primaryKey = 'subscription_id';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = true;
    protected $fillable = [
        'name',
        'email',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}