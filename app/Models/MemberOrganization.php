<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberOrganization extends BaseModel
{
    use HasFactory;

    protected $fillable=[
        'name',
        'slug',
        'municipality_id',
        'district_id',
        'bod_chief',
        'manager',
        'contact_no',
        'phone',
        'email',
        'website',
        'total_shareholder',
        'join_year',
        'status',
        'rank',
        'association',
        'pan_no',
        'reg_no',
        'photo',
        'description'
    ];

    public function district() : BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function municipality() : BelongsTo
    {
        return $this->belongsTo(Municipality::class);
    }
}
