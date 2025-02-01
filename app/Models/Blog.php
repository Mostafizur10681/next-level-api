<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = 'blogs';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = true;
    protected $fillable = [
        'blog_img',
        'blog_img_path',
        'blog_img_name',
        'blog_img_type',
        'blog_title',
        'blog_sub_title', 
        'blog_description',
        'author_img',
        'author_img_path',
        'author_img_name',
        'author_img_type',
        'author_name',
        'active_yn',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

}