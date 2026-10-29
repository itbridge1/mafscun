<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gallery extends BaseModel
{
    use HasFactory;

    protected $fillable=[
        'title',
        'sub_title',
        'slug',
        'status',
        'description',
        'cover_photo',
        'rank'
    ];

    public function images() : HasMany
    {
        return $this->hasMany(GalleryImage::class);
    }
}
