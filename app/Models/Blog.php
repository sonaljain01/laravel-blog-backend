<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;


class Blog extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'deleted_by',
        'isDeleted',
        'photo',
        'parent_category',
        'tag',
        'child_category',
        'slug',
        'type',
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function tags()
    {
        return $this->belongsToMany(Tags::class, 'tags');
    }

    public function parentCategory()
    {
        return $this->belongsTo(ParentCategory::class, 'parent_category');
    }

    public function childCategory()
    {
        return $this->belongsTo(ChildCategory::class, 'child_category');
    }

    public function seoMeta()
    {
        return $this->hasOne(SeoMeta::class);
    }

    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}
