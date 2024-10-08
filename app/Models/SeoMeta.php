<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoMeta extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_id',
        'meta_title',
        'meta_description',
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

}
