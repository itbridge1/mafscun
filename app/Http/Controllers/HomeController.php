<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\BaseController;
use App\Models\AgmForm;
use App\Models\Suggestion;
use App\Models\SiteSetting;
 

class HomeController extends BaseController
{
    protected $panel = 'Dashboard';
    protected $base_route = 'admin.dashboard';
    protected $view_path = 'admin.dashboard';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $site_profile = SiteSetting::first();
        return view($this->loadCommonDataToView('admin.home'),compact('site_profile'));
    }
    public function suggestion(){
        $suggestions = Suggestion::orderBy('id', 'desc')->get();
        return view($this->loadCommonDataToView('admin.suggestionForm.index'), compact('suggestions'));
    }
    public function destroySuggestion($id)
    {
        $suggestion = Suggestion::findOrFail($id);
        $suggestion->delete();

        return redirect()->route('admin.SuggestionForm.index')->with('success', 'Suggestion deleted successfully');
    }
   
}
