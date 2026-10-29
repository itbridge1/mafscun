<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hrm extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'title',
        'slug',
        'photo',
        'rank',
        'description',
        'status'
    ];

    public function parent() : BelongsTo
    {
        return $this->belongsTo(Hrm::class,'parent_id');
    }

    public function children() : HasMany
    {
        return $this->hasMany(Hrm::class,'parent_id');
    }

    public function teams() : HasMany
    {
        return $this->hasMany(Team::class,'hrm_id');
    }
}
