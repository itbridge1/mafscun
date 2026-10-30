<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceSubCategory extends BaseModel
{
    use HasFactory;

    protected $table = 'service_sub_categories';

    protected $fillable = [
        'service_category_id',
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
        return 'serviceSubCategory';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'service_sub_category_id')->orderBy('rank');
    }
}
