<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class AgmForm extends BaseModel
{
    use HasFactory;
     protected $fillable = [
        'name',
        'nepali_name',
        'gender',
        'saccos_union',
        'post',
        'email',
        'district_id',
        'municipality_id',
        'ward_no',
        'membership_no',
        'mobile_no',
        'nomination_form',
        'photo',
        'status',
        'cooperative_name',
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    

}
