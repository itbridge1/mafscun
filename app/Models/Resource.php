<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resource extends BaseModel
{
    use HasFactory;

    protected $table = 'resources';

    protected $fillable = [
        'resource_type_id',
        'resource_sub_type_id',
        'title',
        'sub_title',
        'slug',
        'type',
        'file',
        'description',
        'status',
        'rank',
    ];

    public function code()
    {
        try {
            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $this->file, $match);
            $youtube_id = $match[1];
            return $youtube_id;
        } catch (\Exception $e) {
            return "";
        }
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ResourceType::class, 'resource_type_id');
    }

    public function resourceType(): BelongsTo
    {
        return $this->belongsTo(ResourceType::class, 'resource_type_id');
    }

    public function subType(): BelongsTo
    {
        return $this->belongsTo(ResourceSubType::class, 'resource_sub_type_id');
    }
}
