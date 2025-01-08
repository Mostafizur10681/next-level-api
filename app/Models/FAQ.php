<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
    protected $table = 'FAQ';
    protected $primaryKey = 'faq_id';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = true;
    protected $fillable = [
        'faq_title',
        'faq_description',
        'created_at',
        'created_by',
        'active_yn',
        'updated_at', 
        'updated_by'
    ];

}
