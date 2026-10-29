<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'title',
        'sub_title',
        'slug',
        'menu_url',
        'rank',
        'parent_id',
        'created_by',
        'status'
    ];

    public function parent() : BelongsTo
    {
        return $this->belongsTo(Menu::class,'parent_id');
    }

    public function children() : HasMany
    {
        return $this->hasMany(Menu::class,'parent_id');
    }
}
