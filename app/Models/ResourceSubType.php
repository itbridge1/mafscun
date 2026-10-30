<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResourceSubType extends BaseModel
{
    use HasFactory;

    protected $table = 'resource_sub_types';

    protected $fillable = [
        'resource_type_id',
        'title',
        'slug',
        'description',
        'rank',
        'status',
    ];

    public function resourceType(): BelongsTo
    {
        return $this->belongsTo(ResourceType::class, 'resource_type_id');
    }

    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class, 'resource_sub_type_id')->orderBy('rank');
    }
}
