<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use App\Models\Affiliation;
use App\Models\Hrm;
use App\Models\Menu;
use App\Models\Event;
use App\Models\News;
use App\Models\Notice;
use App\Models\ServiceCategory;
use App\Models\SiteSetting;
use App\Models\ResourceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class BaseController extends Controller
{
    protected function loadCommonDataToView($view_path)
    {
        $serviceCategories = ServiceCategory::select('id', 'title', 'slug')
            ->with([
                'subCategories' => function ($q) {
                    $q->where('status', 1)->rank()
                      ->select('id', 'service_category_id', 'title', 'slug')
                      ->with(['services' => function ($sq) {
                          $sq->where('status', 1)->rank()
                            ->select('id', 'service_category_id', 'service_sub_category_id', 'title', 'slug');
                      }]);
                },
                'directServices' => function ($q) {
                    $q->where('status', 1)->rank()
                      ->select('id', 'service_category_id', 'service_sub_category_id', 'title', 'slug');
                },
            ])
            ->rank()->active()->get();

        $resourceTypes = ResourceType::select('id', 'title', 'slug')
            ->with([
                'subTypes' => function ($q) {
                    $q->where('status', 1)->rank()
                      ->select('id', 'resource_type_id', 'title', 'slug');
                },
            ])
            ->rank()->active()->get();

        $hrmCollection = Hrm::whereNull('parent_id')->rank()->select('slug','title')->get();
        $siteSetting = SiteSetting::first();
        //sending common variables to all path extending BaseController
        View::composer($view_path, function ($view) use ($serviceCategories,$hrmCollection,$siteSetting,$resourceTypes) {
            $view->with('_service_categories', $serviceCategories);
            $view->with('_resource_types', $resourceTypes);
            $view->with('_hrm', $hrmCollection);
            $view->with('_site_setting', $siteSetting);
            $view->with('_dock_notices', Notice::active()->latest()->where('is_career', 0)->limit(4)->get());
            $view->with('_dock_news', News::active()->latest()->limit(4)->get());
            $view->with('_dock_events', Event::active()->latest()->limit(4)->get());
            $dockNotices = Notice::active()->where('is_career', 0)->latest()->limit(8)->get()->map(function ($item) {
                return (object) ['type' => 'notice', 'title' => $item->title, 'slug' => $item->slug, 'date' => $item->created_at, 'start_date' => null, 'end_date' => null, 'summary' => strip_tags($item->description ?? ''), 'url' => route('notices.detail', $item->slug)];
            });
            $dockEvents = Event::active()->latest()->limit(8)->get()->map(function ($item) {
                return (object) ['type' => 'event', 'title' => $item->title, 'slug' => $item->slug, 'date' => $item->start_date ?: $item->created_at, 'start_date' => $item->start_date, 'end_date' => $item->end_date, 'summary' => strip_tags($item->short_description ?: $item->description ?: ''), 'url' => route('events.show', $item->slug)];
            });
            $view->with('_dock_updates', $dockNotices->concat($dockEvents)->sortByDesc('date')->values());
            $view->with('_affiliations', Affiliation::select('title', 'link', 'logo')
                ->active()
                ->rank()
                ->get());
            $view->with('_aboutMenu', AboutUs::select('title','slug')->where('is_message', 0)->rank()->active()->get());
            $view->with('_footer1', $this->getMenuBySiteSetting($siteSetting->footer_1, 20));
            $view->with('_footer2', $this->getMenuBySiteSetting($siteSetting->footer_2, 20));
            $view->with('_footer3', $this->getMenuBySiteSetting($siteSetting->footer_3, 20));
            $view->with('_footer4', $this->getMenuBySiteSetting($siteSetting->footer_4, 20));
        });

        return $view_path;
    }

    public function resourceExist($resource)
    {
        if(!$resource)
            abort(404);
    }

    protected function getMenuBySiteSetting($menu_id,$limit)
    {
        return  Menu::where('menus.id',$menu_id)
            ->with(['children'=> function ($q) use ($limit) {
                $q->where('status',1)
                    ->orderBy('rank')
                    ->limit($limit);
            }])->first();
    }
}
