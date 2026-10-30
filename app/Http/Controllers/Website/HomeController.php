<?php

namespace App\Http\Controllers\Website;

use App\Http\Requests\Website\StoreContactValidation;
use App\Http\Requests\Website\StoreSuggestionValidation;
use App\Models\AboutUs;
use App\Models\ContactForm;
use App\Models\District;
use App\Models\Gallery;
use App\Models\Hrm;
use App\Models\MemberOrganization;
use App\Models\Municipality;
use App\Models\News;
use App\Models\Notice;
use App\Models\Resource;
use App\Models\ResourceType;
use App\Models\ServiceCategory;
use App\Models\SiteSetting;
use App\Models\Slider;
use App\Models\StatisticsDetail;
use App\Models\Statistic;
use App\Models\SuccessStory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Http\Requests\Website\StoreAgmValidation;
use App\Models\AgmForm;
use App\Models\Suggestion;

class HomeController extends BaseController
{
    
    protected $_site_setting;

    public function __construct(){
        $this->_site_setting = SiteSetting::first();      
        
    }

    public function index()
    {
        $data = [];
        $data['_sliders'] = Slider::select('alt_text','caption', 'photo')
            ->active()
            ->rank()
            ->limit(10)
            ->get();



         $data['site_setting_title'] = SiteSetting::pluck('statistics_text')->first();

        // dd( $data['site_setting_title']);
        $data['site_setting_stat'] = StatisticsDetail::orderBy('rank')->get();


        
        $notices = Notice::select('title', 'slug', 'created_at', 'description')
            ->active()
            ->latest()
            ->where('is_career', 0)
            ->limit(4)
            ->get()
            ->map(function($item) {
                return (object)[
                    'type' => 'notice',
                    'title' => $item->title,
                    'slug' => $item->slug,
                    'date' => $item->created_at,
                    'summary' => strip_tags($item->description ?? ''),
                    'url' => route('notices.detail', $item->slug)
                ];
            });

        $events = \App\Models\Event::select('title', 'slug', 'created_at', 'end_date', 'short_description', 'description')
            ->active()
            ->latest()
            ->limit(4)
            ->get()
            ->map(function($item) {
                return (object)[
                    'type'     => 'event',
                    'title'    => $item->title,
                    'slug'     => $item->slug,
                    'date'     => $item->created_at,
                    'end_date' => $item->end_date,
                    'summary'  => strip_tags($item->description ?: ($item->short_description ?? '')),
                    'url'      => route('events.show', $item->slug)
                ];
            });

        $data['_notices_events'] = $notices->concat($events)->sortByDesc('date');

$data['_serviceslist'] = ServiceCategory::select('title','slug','photo')->active()->whereNull('parent_id')->get();

        $data['_latest_news'] = News::active()->latest()->select('title', 'slug')->limit(10)->get();

        $data['_popup'] = Notice::where('is_popup',1)->active()->get();


        // Featured About Us: top ranked general About Us page (or fallback to about_1 setting)
        $data['_aboutUs'] = AboutUs::select('title', 'slug', 'photo', 'description')
            ->where('is_message', 0)
            ->active()
            ->rank()
            ->first();

        if (!$data['_aboutUs'] && $this->_site_setting && $this->_site_setting->about_1) {
            $data['_aboutUs'] = AboutUs::select('title', 'slug', 'photo', 'description')
                ->where('id', $this->_site_setting->about_1)
                ->first();
        }

        // Messages & Commitment list: any AboutUs with is_message = 1
        $data['_messages'] = AboutUs::select('title', 'slug', 'photo', 'description', 'sub_title')
            ->where('is_message', 1)
            ->active()
            ->rank()
            ->get();
        $data['_success_stories'] = SuccessStory::select('name','designation','phone','email','organization',
            'description', 'photo')
            ->active()
            ->rank()
            ->limit(6)
            ->get();
        $data['_statistics'] = $this->getStatisticsData();
        $data['_total'] = $data['_statistics']->sum('total');
        $data['_associated'] = $data['_statistics']->sum('associated');
        $data['districts'] = District::where('province_id',3)
            ->active()
            ->select('name','id')
            ->get();

        $this->_site_setting->markAsRead();



        return view($this->loadCommonDataToView('website.home.index'), compact('data'));
    }



    //aboutUSPage
    public function about()
    {
        $_aboutUs = AboutUs::where('is_message', 0)->active()->rank()->first();
        if (!$_aboutUs && $this->_site_setting && $this->_site_setting->about_1) {
            $_aboutUs = AboutUs::where('id', $this->_site_setting->about_1)->first();
        }
        $_aboutUsList = AboutUs::where('is_message', 0)
            ->when($_aboutUs, function($q) use ($_aboutUs) {
                return $q->where('id', '!=', $_aboutUs->id);
            })
            ->rank()
            ->active()
            ->paginate(10);
		
        return view($this->loadCommonDataToView('website.about.index'), compact('_aboutUs', '_aboutUsList'));
    }

    //aboutUSPage Detail
    public function aboutDetail($slug)
    {
        $_aboutUs = AboutUs::where('slug', $slug)->first();
        $this->resourceExist($_aboutUs);
        $_otherAboutUs = AboutUs::where('is_message', $_aboutUs->is_message ? 1 : 0)
            ->where('id', '!=', $_aboutUs->id)
            ->rank()
            ->active()
            ->get();

        return view($this->loadCommonDataToView('website.about.detail'), compact('_aboutUs', '_otherAboutUs'));
    }

    //News Page
    public function news()
    {
        $_news = News::select('photo','title','slug','description','created_at')->active()
            ->latest()
            ->paginate(18);

        return view($this->loadCommonDataToView('website.news.index'),compact('_news'));
    }

    //News Detail Page
    public function newsDetail($slug)
    {
        $_news = News::with('parent')->active()->where('slug',$slug)->first();
        $this->resourceExist($_news);
        $_news->markAsRead();

        $_trending_news = News::select('title','slug','author','location','id')
            ->except($_news->id)
            ->trending()
            ->active()
            ->limit(10)
            ->get();

        $trendingExistingId = $_trending_news->pluck('id')->toArray();

        $_other_news = News::where('category_id', $_news->category_id)
            ->except(array_merge($trendingExistingId,[$_news->id]))
            ->latest()
            ->limit(6)
            ->get();

        return view($this->loadCommonDataToView('website.news.detail'),compact('_news','_trending_news', '_other_news'));
    }

    //gallery Page
    public function gallery()
    {
        $_gallery = Gallery::active()
            ->rank()
            ->paginate(12);

        return view($this->loadCommonDataToView('website.gallery.index'),compact('_gallery'));
    }

    //gallery Detail Page
    public function galleryDetail($slug)
    {
        $_gallery = Gallery::with('images')->where('slug',$slug)->active()->first();
        $this->resourceExist($_gallery);
        $_other_galleries = Gallery::except($_gallery->id)->active()->limit(6)->rank()->get();

        return view($this->loadCommonDataToView('website.gallery.detail'),compact('_gallery','_other_galleries'));
    }

    public function contact()
    {
        $_contactUs = SiteSetting::where('id',$this->_site_setting->about_1)->first();

        return view($this->loadCommonDataToView('website.contact.index'),compact('_contactUs'));
    }

    public function contactStore(StoreContactValidation $request)
    {
//        dd($request->validated());
        if($request->filled('captcha')){
            $request->session()->flash('error_message', 'Dear '.$request->name. ', Something went wrong while submitting the form. Please try again');
        }else{
            $contactForm = ContactForm::create($request->validated());
            $request->session()->flash('success_message', 'Dear '.$request->name. ', Your form has been successfully received! Stay in touch with us. thank you');
        }
        return redirect()->route('contact');
    }

    public function hrmDetail($slug)
    {
        $_hrmDetail = Hrm::with(
            ['teams' => function($query){
                $query->select('id','name','designation','photo','phone', 'email','hrm_id','rank')
                    ->rank()
                    ->active();
            }]
        )->where('hrms.slug', $slug)
            ->select('title','slug', 'photo', 'description','id')
            ->first();
        $this->resourceExist($_hrmDetail);

        $rowForRole = function ($team) use ($_hrmDetail) {
            $role = trim((string) $team->designation);
            if (str_contains($_hrmDetail->slug, 'board')) {
                if ($role === 'अध्यक्ष') return 1;
                if (preg_match('/उपाध्यक्ष|सचिव|कोषाध्यक्ष/u', $role)) return 2;
                return 3;
            }
            if (str_contains($_hrmDetail->slug, 'audit')) {
                return str_contains($role, 'संयोजक') ? 1 : 2;
            }
            if (str_contains($_hrmDetail->slug, 'staff')) {
                return preg_match('/व्यवस्थापक|ब्यवस्थापक/u', $role) ? 1 : 2;
            }
            return 2;
        };
        $_hrmDetail->teamRows = $_hrmDetail->teams
            ->sortBy(fn ($team) => ($rowForRole($team) * 1000) + ($team->rank ?? $team->id))
            ->groupBy($rowForRole);

        return view($this->loadCommonDataToView('website.hrm.detail'),compact('_hrmDetail'));
    }

    public function service()
    {
        $_services = ServiceCategory::active()->whereNull('parent_id')->rank()->paginate(10);

        return view($this->loadCommonDataToView('website.services.index'),compact('_services'));
    }

    public function serviceDetail($slug)
    {
        // 1. Try finding ServiceCategory
        $serviceCategory = ServiceCategory::with([
            'subCategories' => function ($q) {
                $q->active()->rank()->with(['services' => function ($sq) {
                    $sq->active()->rank();
                }]);
            },
            'directServices' => function ($q) {
                $q->active()->rank();
            },
            'children' => function ($q) {
                $q->active()->rank();
            }
        ])->where('slug', $slug)->active()->first();

        if ($serviceCategory) {
            $_service = $serviceCategory;
            if ($serviceCategory->subCategories->count() > 0) {
                $_service->children = $serviceCategory->subCategories;
            } elseif ($serviceCategory->directServices->count() > 0) {
                $_service->children = $serviceCategory->directServices;
            }
            return view($this->loadCommonDataToView('website.services.index'), compact('_service'));
        }

        // 2. Try finding ServiceSubCategory
        $serviceSubCategory = \App\Models\ServiceSubCategory::with([
            'category',
            'services' => function ($q) {
                $q->active()->rank();
            }
        ])->where('slug', $slug)->active()->first();

        if ($serviceSubCategory) {
            $_service = $serviceSubCategory;
            $_service->children = $serviceSubCategory->services;
            return view($this->loadCommonDataToView('website.services.index'), compact('_service'));
        }

        // 3. Try finding Service
        $serviceItem = \App\Models\Service::with(['category', 'subCategory'])
            ->where('slug', $slug)->active()->first();

        if ($serviceItem) {
            $_service = $serviceItem;
            return view($this->loadCommonDataToView('website.services.index'), compact('_service'));
        }

        abort(404);
    }

    public function resources(Request $request)
    {
        $_resourceTypes = ResourceType::active()->pluck('title','id');
        $resourceType = $request->input('filter_resource_type');
        $filterSubType = $request->input('filter_sub_type');
        $filterTitle = trim((string) $request->input('filter_title', ''));

        $_resources = Resource::with(['parent:id,title', 'subType:id,title'])->active()
            ->when($resourceType !== null && $resourceType !== '', function ($query) use ($resourceType) {
                $query->where('resource_type_id', $resourceType);
            })
            ->when($filterSubType !== null && $filterSubType !== '', function ($query) use ($filterSubType) {
                $query->where('resource_sub_type_id', $filterSubType);
            })
            ->when($filterTitle !== '', function ($query) use ($filterTitle) {
                $query->where('title', 'like', '%' . $filterTitle . '%');
            })
            ->latest()->paginate(20)
            ->appends($request->only(['filter_resource_type', 'filter_sub_type', 'filter_title']));

        return view($this->loadCommonDataToView('website.resource.index'),compact('_resources','_resourceTypes'));
    }

    public function notice()
    {
        $_notices = Notice::active()->exceptCareer()
            ->orderByRaw('CASE WHEN start_date IS NULL AND end_date IS NULL THEN 1 ELSE 0 END')
            ->orderByDesc('id')
            ->paginate(20);

        return view($this->loadCommonDataToView('website.notice.index'),compact('_notices'));
    }

    public function noticeDetail($slug)
    {
        $_notice = Notice::where('slug', $slug)
            ->active()
            ->first();
        $this->resourceExist($_notice);
        $head = null;
        if($_notice->is_career){$head = "Career";}
        $_other_notices = Notice::except($_notice->id)->exceptCareer()->active()->latest()->limit(10)->get();

        return view($this->loadCommonDataToView('website.notice.detail'),compact('_notice', '_other_notices','head'));
    }

    public function career()
    {
        $today = now()->format('Y-m-d');
        $_notices = Notice::active()->career()
            ->where('start_date', '<=', Carbon::now())
                ->where('end_date', '>=', Carbon::now())
//            ->whereRaw('(now() between start_date and end_date)')
            ->latest()
            ->paginate(20);

        return view($this->loadCommonDataToView('website.notice.career'),compact('_notices'));
    }

    public function organization()
    {
        $_organizations = MemberOrganization::active()->rank()->paginate(50);

        return view($this->loadCommonDataToView('website.about.organization'),compact('_organizations'));
    }

    protected function getStatisticsData(){
        $statistics = Statistic::select('statistics.district_id', 'statistics.municipality_id',
            'statistics.total', 'statistics.associated','statistics.id', 'd.name as district_name',
            'd.photo as district_photo',
            'd.autoselect as district_autoselect',
            'm.name as municipality_name')
            ->join('districts as d', 'd.id', '=','statistics.district_id')
            ->join('municipalities as m', 'm.id', '=','statistics.municipality_id')
            ->get()
            ->groupBy('district_id');

        $filteredStatistics = $statistics->filter(function ($value, $key) {
            $value->d_name = $value->first()->district_name;
            $value->d_map = $value->first()->district_photo;
            $value->d_autoselect = (int) ($value->first()->district_autoselect ?? 0);
            $value->m_name = Arr::pluck($value, 'municipality_name');
            //finding total of District
            $total = Arr::pluck($value, 'total');
            $filtered_total  =  Arr::where($total, function ($value, $key) {
                return is_numeric($value);
            });
            $grand_total = array_sum($filtered_total);
            $value->total = $grand_total;

            //finding total of aggregate
            $associated = Arr::pluck($value, 'associated');
            $filtered_associated  =  Arr::where($associated, function ($value, $key) {
                return is_numeric($value);
            });
            $grand_associated = array_sum($filtered_associated);
            $value->associated = $grand_associated;
            $value->color = "#".substr(str_shuffle('ABCDEF0123456789'), 0, 6);
            $doubleFilter = $value->filter(function ($val, $k){
               $val->color =  "#".substr(str_shuffle('ABCDEF0123456789'), 0, 6);

               return $val;
            });

            return $value;
        });

        return $statistics;
    }
    public function agmform()
    {
        $data['districts']= District::bagmati()->orderBy('name')
            ->select('name','id')
            ->get();
        $data['municipalities']= Municipality::bagmati()->orderBy('name')
            ->select('name','id','district_id')
            ->get();
        return view($this->loadCommonDataToView('website.agmform.index'),compact('data'));
    }
    public function suggestion()
    {
        return view($this->loadCommonDataToView(view_path: 'website.suggesionForm.index'));
    }
    public function suggestionStore(StoreSuggestionValidation $request)
    {
        $data = $request->validated();
        $agmForm = Suggestion::create($data);
        $request->session()->flash('success_message', 'Dear '.$request->name. ', Your form has been successfully received! Stay in touch with us. thank you');
        return redirect()->route('agmform');
    }
  
    public function AgmVerification($encodedId)
    {
        $id = base64_decode($encodedId);
        $agm = AgmForm::where('id', $id)->first();
        if ($agm) {
            $data['success'] =1;
        } else {
            $data['success'] =0;
        }
        return view($this->loadCommonDataToView('website.agmVerification.index'), compact('agm', 'data'));
    }
       
}

