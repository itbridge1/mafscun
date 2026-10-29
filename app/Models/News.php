<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class News extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'sub_title',
        'slug',
        'photo',
        'view_count',
        'description',
        'author',
        'location',
        'status'
    ];

    public function parent() : BelongsTo
    {
        return $this->belongsTo(News::class,'category_id');
    }

    public function scopeTrending($query)
    {
        return $query->orderBy('view_count','desc');
    }

    public function markAsRead()
    {
        $this->view_count++;
        $this->save();
    }
}
