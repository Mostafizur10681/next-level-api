<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsLetter extends Model
{
    protected $table = 'newsletter';
    protected $primaryKey = 'newsletter_id';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = true;
    protected $fillable = [
        'email',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}