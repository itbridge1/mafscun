<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use HasFactory;

    public function scopeActive($query)
    {
        return $query->where('status',1);
    }

    public function scopeRank($query)
    {
        return $query->orderBy('rank');
    }

    public function scopePaginated($query)
    {
        return $query->paginate(config('helper.pagination_limit'));
    }

    public function scopeExcept($query, $value)
    {
        if(is_array($value))
            return $query->whereNotIn('id', $value??[]);
        return $query->where('id', '!=', $value ?? null);
    }

    public function scopeBagmati($query)
    {
        return $query->where('province_id',3);
    }
}
