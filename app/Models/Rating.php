<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "blog_id",
        "rating"
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function blog()
    {
        return $this->belongsTo(Blog::class, 'blog_id');
    }

}
