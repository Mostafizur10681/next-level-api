<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    protected $table = 'services';
    protected $primaryKey = 'service_id';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = true;
    protected $fillable = [
        'service_name',
        'service_types_id',
        'service_min_price',
        'service_max_price',
        'service_title',
        'service_description',
        'service_attachment',
        'created_by',
        'updated_by',
    ];

}
