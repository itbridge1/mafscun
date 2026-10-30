<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResourceType extends BaseModel
{
    use HasFactory;

    protected $table = 'resource_types';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'status',
        'rank',
    ];

    public function subTypes(): HasMany
    {
        return $this->hasMany(ResourceSubType::class, 'resource_type_id')->orderBy('rank');
    }

    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class, 'resource_type_id')->orderBy('rank');
    }

    public function directResources(): HasMany
    {
        return $this->hasMany(Resource::class, 'resource_type_id')
            ->whereNull('resource_sub_type_id')
            ->orderBy('rank');
    }
}
