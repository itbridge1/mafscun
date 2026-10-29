<?php

namespace App\Helper;

use App\Models\Bill;
use App\Models\SiteProfile;
use App\Models\SiteSetting;
use App\Models\Statistic;
use App\Models\Test;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class ViewHelper{

    public function formatDate($date, $format= 'jS M, Y H:i:s')
    {
        return date($format, strtotime($date));
    }

    public function getImagePath($folder = null, $image_name = null, $dimension = '')
    {
        if (!$folder || !$image_name)
            return asset('images/no-image.png');

        if (!file_exists(public_path('images' . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $image_name)))
            return asset('images/'.$folder.'/no-image.png');

        return asset('images/' . $folder . '/' .$dimension.$image_name);
    }

    public function getSiteLogo($image_name)
    {
        if (file_exists(public_path('images' . DIRECTORY_SEPARATOR .'siteSetting' . DIRECTORY_SEPARATOR . $image_name)))
            return asset('images/siteSetting/'.$image_name);
        return asset('front/img/magazine.png');
    }

    public function checkServiceURL()
    {
        $currentRoute = Route::current()->getName();
        if($currentRoute == "admin.service.index"
            || $currentRoute == "admin.service.create"
            || $currentRoute == "admin.service.edit"
            || $currentRoute == "admin.service.show")
            return true;
        return false;
    }

    public function getValueFromStat($district_id, $municipality_id, $column)
    {
        $stat = Statistic::where('district_id', $district_id)->where('municipality_id', $municipality_id)
            ->first();
        $value = $stat ? $stat->{$column} : 0;

        return  $value;
    }

    public function getStrippedContent($content, $limit)
    {
        return Str::limit(strip_tags($content), $limit);
    }

    public function getMaxRank($model, $field='rank') : int
    {
        if(!$model)
            return 0;
//        dd($model);
        return $model::max($field) + 1;
    }

    public function viewed()
    {
        $siteSetting = SiteSetting::first();
        if($siteSetting){
            $siteSetting->view_count++;
            $siteSetting->save();
        }
    }

}
