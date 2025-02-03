<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    protected $table = 'contactus';
    protected $primaryKey = 'contact_id';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = true;
    protected $fillable = [
        'name',
        'email',
        'service_id',
        'message',
        'active_yn',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}