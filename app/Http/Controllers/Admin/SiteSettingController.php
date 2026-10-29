<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Municipality\StoreMunicipalityValidation;
use App\Http\Requests\Admin\Municipality\UpdateMunicipalityValidation;
use App\Http\Requests\Admin\SiteSetting\UpdateSiteSettingValidation;
use App\Models\AboutUs;
use App\Models\District;
use App\Models\Menu;
use App\Models\Municipality;
use App\Models\Province;
use App\Models\ServiceCategory;
use App\Models\SiteSetting;
use App\Models\Statistic;
use App\Models\StatisticsDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiteSettingController extends BaseController
{
    //properties
    protected $base_route = 'admin.siteSetting';
    protected $view_path = 'admin.siteSetting';
    protected $panel = 'SiteSetting';
    protected $folder = 'siteSetting';
    protected $folder_path;


    public function __construct(SiteSetting $model)
    {
        $this->model = $model;
        $this->folder_path = 'images' . DIRECTORY_SEPARATOR . $this->folder;
        $this->generateAllMiddlewareByPermission();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = [];
        $data['aboutUs'] = AboutUs::active()->pluck('title','id');
        $data['categories'] = ServiceCategory::active()->whereNull('parent_id')->pluck('title','id');
        $data['menus'] = Menu::active()->whereNull('parent_id')->pluck('title','id');
        $data['row'] = SiteSetting::first();

        return view(parent::loadCommonDataToView($this->view_path . '.create'),compact('data'));
    }
    /**o
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSiteSettingValidation $request)
    {
        $row = $this->model->first();

        $data = $request->validated();
        $data['site_logo'] = $this->storeImage($request, 'main_logo', 'site_logo', $row);
        $data['site_favicon'] = $this->storeImage($request, 'main_favicon', 'site_favicon', $row);
        $data['company_logo'] = $this->storeImage($request, 'main_company_logo', 'company_logo', $row);
        $data['powered_by_logo'] = $this->storeImage($request, 'main_powered_by_logo', 'powered_by_logo', $row);
        $data['stat_map'] = $this->storeImage($request, 'main_stat_map', 'stat_map', $row);
 
        if ($row) {
            $row->update($data);
            if ($request->has('statisticsData')) {
                // Get all submitted IDs
                $submittedIds = [];
                foreach ($request->get('statisticsData') as $item) {
                    if (isset($item['id']) && $item['id']) {
                        $submittedIds[] = $item['id'];
                    }
                }

                // Delete records that are no longer in the submitted list
                $existingIds = StatisticsDetail::where('site_setting_id', $row->id)->pluck('id')->toArray();
                $deleteIds = array_diff($existingIds, $submittedIds);
                
                if (!empty($deleteIds)) {
                    $toDelete = StatisticsDetail::whereIn('id', $deleteIds)->get();
                    foreach ($toDelete as $deleteRow) {
                        if ($deleteRow->photo) {
                            @$this->removeFile($this->folder_path . DIRECTORY_SEPARATOR . $deleteRow->photo);
                        }
                        $deleteRow->delete();
                    }
                }

                foreach ($request->get('statisticsData') as $key => $item) {
                    $existingRow = null;
                    if (isset($item['id']) && $item['id']) {
                        $existingRow = StatisticsDetail::find($item['id']);
                    }
                    
                    $statPhoto = $this->storeImage($request, 'stat_photo_' . $key, 'photo', $existingRow);
                    
                    StatisticsDetail::updateOrCreate(
                        [
                            'id' => $item['id'] ?? null,
                        ],
                        [
                            'site_setting_id' => $row->id,
                            'name' => $item['name'],
                            'value' => $item['value'],
                            'rank' => $item['rank'],
                            'photo' => $statPhoto,
                        ]
                    );
                }
            }
        } else {
            $row = $this->model->create($data);
            if ($request->has('statisticsData')) {
                foreach ($request->get('statisticsData') as $key => $item) {
                    $existingRow = null;
                    if (isset($item['id']) && $item['id']) {
                        $existingRow = StatisticsDetail::find($item['id']);
                    }
                    
                    $statPhoto = $this->storeImage($request, 'stat_photo_' . $key, 'photo', $existingRow);
                    
                    StatisticsDetail::updateOrCreate(
                        [
                            'id' => $item['id'] ?? null,
                        ],
                        [
                            'site_setting_id' => $row->id,
                            'name' => $item['name'],
                            'value' => $item['value'],
                            'rank' => $item['rank'],
                            'photo' => $statPhoto,
                        ]
                    );
                }
            }
        }

        $request->session()->flash('success_message', $this->panel . ' ' . $row->title . ' updated Successfully');

        return redirect()->route($this->base_route . '.index');
    }

    protected function storeImage($request, $fileKey,$databaseKey,$row=null,){

        if($request->hasFile($fileKey)){
            $this->processImage($request[$fileKey]);
            $fileName =$this->file_name;
            //remove
            if($row){
                @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.$row[$databaseKey]);
            }
            return $fileName;
        }else{
            if($row){
                return $row[$databaseKey];
            }else{
                return null;
            }
        }
    }
}
