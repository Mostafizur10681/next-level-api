<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChooseUs extends Model
{
    protected $table = 'why_choose_us';
    protected $primaryKey = 'choose_id';
    public $incrementing = true;
    //protected $keyType = 'int';

    public $timestamps = true;
    protected $fillable = [
        'title',
        'description',
        'created_by',
        'updated_by',
        'active_yn',
        'updated_at', 
        'created_at'
    ];

}
