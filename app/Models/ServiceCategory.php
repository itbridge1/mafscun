<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceCategory extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'title',
        'sub_title',
        'slug',
        'photo',
        'rank',
        'description',
        'status'
    ];

    public function parent() : BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'parent_id');
    }

    public function children() : HasMany
    {
        return $this->hasMany(ServiceCategory::class, 'parent_id');
    }

    public function getPhotoFolderAttribute(): string
    {
        return 'serviceCategory';
    }

    public function subCategories() : HasMany
    {
        return $this->hasMany(ServiceSubCategory::class, 'service_category_id')->orderBy('rank');
    }

    public function services() : HasMany
    {
        return $this->hasMany(Service::class, 'service_category_id')->orderBy('rank');
    }

    public function directServices() : HasMany
    {
        return $this->hasMany(Service::class, 'service_category_id')
            ->whereNull('service_sub_category_id')
            ->orderBy('rank');
    }
}
