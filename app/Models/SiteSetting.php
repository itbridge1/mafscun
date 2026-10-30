<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'sub_title',
        'site_logo',
        'site_favicon',
        'company_logo',
        'stat_map',
        'company_alias',
        'pan_no',
        'reg_no',
        'email',
        'phone',
        'location',
        'statistics_text',
        'footer_text',
        'footer_1',
        'footer_2',
        'footer_3',
        'footer_4',
        'about_1',
        'about_2',
        'about_3',
        'about_4',
        'map',
        'social',
        'copyright_text',
        'powered_by',
        'powered_by_link',
        'powered_by_logo',
        'keywords',
        'description',
        'google_site_verification',
        'view_count',
        'primary_color',
        'secondary_color',
    ];

    protected $casts = [
        'social'     => 'array',
    ];

    public function markAsRead()
    {
        $this->view_count++;
        $this->save();
    }
    public function statistics(): HasMany
    {
        return $this->hasMany(StatisticsDetail::class);
    }
}
