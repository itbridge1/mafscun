<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hrm;
use App\Models\ServiceCategory;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;

class BaseController extends Controller
{
    public  $model;
    public $file_name;

    protected function loadCommonDataToView($view_path)
    {
        $serviceCategories = ServiceCategory::whereNull('parent_id')->rank()->select('slug','title')->get();
        $hrmCollection = Hrm::whereNull('parent_id')->rank()->select('slug','title')->get();

        //sending common variables to all path extending BaseController
        View::composer($view_path, function ($view) use ($serviceCategories,$hrmCollection) {
            $view->with('dashboard_url', route('admin.dashboard'));
            $view->with('_panel',$this->panel);
            $view->with('_base_route',$this->base_route);
            $view->with('_view_path',$this->base_route);
            $view->with('_folder',property_exists($this,'folder')?$this->folder:'');
            $view->with('_service_categories',$serviceCategories);
            $view->with('_hrm',$hrmCollection);
            $view->with('_site_setting',SiteSetting::first());
        });


        return $view_path;

    }

    public function checkDirectoryExist (){
        //check folder
        if (!file_exists(public_path($this->folder_path)))
        {
            @mkdir(public_path($this->folder_path), 0777, true);
        }
    }
    

    public function getRandomStringForImage()
    {
        return rand(2323,9999);
    }

    public function rowExist( $row)
    {
        if (!$row) {
            request()->session()->flash('error_message', 'Invalid request');
            return redirect()->route($this->base_route.'.index')->send();
        }

    }

    public function parentExist($row)
    {
        if (!$row) {
            request()->session()->flash('error_message', 'Could Not Find the Requested Resource');
            return redirect()->route('admin.dashboard')->send();
        }
    }

    protected function processImage($file, $dimension_conf = null)
    {
        $this->file_name = time().'_'.$this->getRandomStringForImage().'.'. $file->getClientOriginalExtension();
        //check if folder exist
        $this->checkDirectoryExist();

        $file->move(public_path($this->folder_path), $this->file_name);

        if($dimension_conf) {
            //   upload thumb images
            $image_thumb_config = $dimension_conf;
            foreach ($image_thumb_config as $thumb_config){

                $image = Image::make(public_path($this->folder_path).DIRECTORY_SEPARATOR.$this->file_name);
                $image-> resize($thumb_config['width'], $thumb_config['height']);
//                $image->resize($thumb_config['width'],$thumb_config['height'],function($constraint){
//                    $constraint->aspectRatio();
//                });
                $image->save(public_path($this->folder_path). DIRECTORY_SEPARATOR . $thumb_config['width'].'_'.$thumb_config['height'].'_'.$this->file_name);
            }
        }

    }
   

    protected function removeFile($path)
    {
        if (file_exists(public_path($path)))
            @unlink(public_path($path));
    }

    function generateAllMiddlewareByPermission($permissionName = null)
    {
        //Take permission name automatically from panel
        if (!$permissionName)
            $permissionName = lcfirst(string: $this->panel);

        $this->middleware('permission:' . $permissionName.'-index')->only('index');
        $this->middleware('permission:' . $permissionName.'-view')->only('show');
        $this->middleware('permission:' . $permissionName . '-create')->only('create', 'store');
        $this->middleware('permission:' . $permissionName . '-edit')->only('edit', 'update');
        $this->middleware('permission:' . $permissionName . '-delete')->only('destroy');
    }
}
