<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resource extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'resource_type_id',
        'title',
        'sub_title',
        'slug',
        'type',
        'file',
        'description',
        'status',
        'rank',
    ];

    function code()
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
        return $this->belongsTo(Resource::class, 'resource_type_id');
    }
}
