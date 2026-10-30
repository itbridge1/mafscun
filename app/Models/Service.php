<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends BaseModel
{
    use HasFactory;

    protected $table = 'services';

    protected $fillable = [
        'service_category_id',
        'service_sub_category_id',
        'title',
        'sub_title',
        'slug',
        'photo',
        'rank',
        'description',
        'status'
    ];

    public function getPhotoFolderAttribute(): string
    {
        return 'service';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(ServiceSubCategory::class, 'service_sub_category_id');
    }
}
