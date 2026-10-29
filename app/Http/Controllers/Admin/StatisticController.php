<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Municipality\StoreMunicipalityValidation;
use App\Http\Requests\Admin\Municipality\UpdateMunicipalityValidation;
use App\Models\District;
use App\Models\Municipality;
use App\Models\Province;
use App\Models\Statistic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticController extends BaseController
{
    //properties
    protected $base_route = 'admin.statistic';
    protected $view_path = 'admin.statistic';
    protected $panel = 'Statistic';
    protected $folder = 'statistic';
    protected $folder_path;


    public function __construct(Statistic $model)
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
        $data['provinces'] = Province::active()->pluck('province_name', 'id');
        $data['districts'] = District::where('province_id',3)->with('municipalities')
            ->active()
            ->get();
        $data['existingData'] = Statistic::get()->groupBy('district_id');

//        dd($data['existingData']);

        return view(parent::loadCommonDataToView($this->view_path . '.create'),compact('data'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        foreach ($request->detail as $key => $district){
            $existingDistrict = Statistic::where('district_id', $district['district_id'])
                ->where('municipality_id', $district['municipality_id'])
                ->first();
                if($existingDistrict){
                    $existingDistrict->update($district);
                }else{
                    Statistic::create($district);
                }
        }

        $request->session()->flash('success_message', $this->panel.' '. 'added Successfully');
        return redirect()->route($this->base_route.'.index');
    }
}
