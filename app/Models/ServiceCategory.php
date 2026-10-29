<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        return  $this->belongsTo(ServiceCategory::class,'parent_id');
    }

    public function children() : HasMany
    {
        return  $this->hasMany(ServiceCategory::class,'parent_id');
    }
}
