<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LServiceType extends Model
{
    protected $table = 'l_service_type';
    protected $primaryKey = 'service_type_id';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = true;
    protected $fillable = [
        'service_type_name',
        'created_by',
        'active_yn',
        'updated_by',
    ];

}
