<?php

use App\Http\Controllers\Admin\AgmFormController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\PaymentQrController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    return redirect()->back()->with('message', 'Cache cleared and application optimized.');
});

 

Route::get('/', [\App\Http\Controllers\Website\HomeController::class,'index'])->name('index');

// form
Route::get('/agmform', [\App\Http\Controllers\Website\HomeController::class,'agmform'])->name('agmform');
Route::post('/agmform/store', [AgmFormController::class,'agmformStore'])->name('agmform.store');

Route::get('/suggestion', [\App\Http\Controllers\Website\HomeController::class,'suggestion'])->name('suggestion');
 Route::post('/suggestion/store', [\App\Http\Controllers\Website\HomeController::class,'suggestionStore'])->name('suggestionform.store');
Route::get('municipalities', [App\Http\Controllers\Admin\MunicipalityController::class, 'getMunicipalities'])->name('municipalities.get');

// ---- Website Event Routes ----
Route::get('events', [\App\Http\Controllers\Website\EventController::class, 'index'])->name('events.index');
Route::get('events/{slug}', [\App\Http\Controllers\Website\EventController::class, 'show'])->name('events.show');
Route::get('event/booking/ticket/{token}', [\App\Http\Controllers\Website\EventController::class, 'publicCards'])->name('event.booking.ticket');
Route::get('event/booking/ticket/{token}/download', [\App\Http\Controllers\Website\EventController::class, 'downloadPublicCards'])->name('event.booking.ticket.download');
// Website User Auth Routes
Route::get('login', [\App\Http\Controllers\Website\AuthController::class, 'showLogin'])->name('login');
Route::post('login/send-otp', [\App\Http\Controllers\Website\AuthController::class, 'sendOtp'])->name('login.send-otp');
Route::post('login/verify-otp', [\App\Http\Controllers\Website\AuthController::class, 'verifyOtp'])->name('login.verify-otp');
Route::post('login/password', [\App\Http\Controllers\Website\AuthController::class, 'loginWithPassword'])->name('login.password');
Route::post('logout-user', [\App\Http\Controllers\Website\AuthController::class, 'logout'])->name('logout.user');

Route::middleware(['auth'])->group(function () {

    // ── Forced password setup for OTP-registered users ──
    Route::get('account/set-password',  [\App\Http\Controllers\Website\AuthController::class, 'showSetPassword'])->name('user.set-password');
    Route::post('account/set-password', [\App\Http\Controllers\Website\AuthController::class, 'storeSetPassword'])->name('user.set-password.store');

    // ── All routes below require the user to have already set a password ──
    Route::middleware(['force.password.set'])->group(function () {
        Route::get('events/{slug}/book', [\App\Http\Controllers\Website\EventController::class, 'bookStep1'])->name('events.book.step1');
        Route::post('events/{slug}/book/step1', [\App\Http\Controllers\Website\EventController::class, 'postStep1'])->name('events.book.postStep1');
        Route::get('events/{slug}/book/step2', [\App\Http\Controllers\Website\EventController::class, 'bookStep2'])->name('events.book.step2');
        Route::post('events/{slug}/book/step2', [\App\Http\Controllers\Website\EventController::class, 'postStep2'])->name('events.book.postStep2');
        Route::get('events/{slug}/book/step3', [\App\Http\Controllers\Website\EventController::class, 'bookStep3'])->name('events.book.step3');
        Route::post('events/{slug}/book/store', [\App\Http\Controllers\Website\EventController::class, 'bookStore'])->name('events.book.store');
        
        Route::get('events/booking/continue/{id}', [\App\Http\Controllers\Website\EventController::class, 'continueBooking'])->name('events.booking.continue');

        Route::get('events/booking/success/{token}', [\App\Http\Controllers\Website\EventController::class, 'bookingSuccess'])->name('events.booking.success');
        Route::post('booking/success/{token}/voucher', [\App\Http\Controllers\Website\EventController::class, 'uploadVoucher'])->name('events.booking.upload-voucher');

        Route::get('dashboard', [\App\Http\Controllers\Website\DashboardController::class, 'index'])->name('user.dashboard');
        Route::get('profile', [\App\Http\Controllers\Website\DashboardController::class, 'profile'])->name('user.profile');
        Route::post('profile/update', [\App\Http\Controllers\Website\DashboardController::class, 'updateProfile'])->name('user.profile.update');
        Route::get('dashboard/card/{id}/download', [\App\Http\Controllers\Website\DashboardController::class, 'downloadCard'])->name('user.card.download');
        Route::get('dashboard/booking/{id}/cards', [\App\Http\Controllers\Website\DashboardController::class, 'downloadBookingCards'])->name('user.booking.cards');
        Route::get('dashboard/booking/{id}/reapply', [\App\Http\Controllers\Website\DashboardController::class, 'reapplyBooking'])->name('user.booking.reapply');
    });
});

Route::get('organization', [\App\Http\Controllers\Website\HomeController::class,'organization'])->name('organization');
Route::get('about-us', [\App\Http\Controllers\Website\HomeController::class,'about'])->name('about');
Route::get('about-us/{slug}', [\App\Http\Controllers\Website\HomeController::class,'aboutDetail'])->name('about.detail');

Route::get('hrm', [\App\Http\Controllers\Website\HomeController::class,'hrm'])->name('hrm');
Route::get('hrm/{slug}', [\App\Http\Controllers\Website\HomeController::class,'hrmDetail'])->name('hrm.detail');

Route::get('news', [\App\Http\Controllers\Website\HomeController::class,'news'])->name('news');
Route::get('news/{slug}', [\App\Http\Controllers\Website\HomeController::class,'newsDetail'])->name('news.detail');

Route::get('notices', [\App\Http\Controllers\Website\HomeController::class,'notice'])->name('notices');
Route::get('career', [\App\Http\Controllers\Website\HomeController::class,'career'])->name('career');
Route::get('notices/{slug}', [\App\Http\Controllers\Website\HomeController::class,'noticeDetail'])->name('notices.detail');

Route::get('service', [\App\Http\Controllers\Website\HomeController::class,'service'])->name('service');
Route::get('service/{slug}', [\App\Http\Controllers\Website\HomeController::class,'serviceDetail'])->name('service.detail');

Route::get('resource-list',[\App\Http\Controllers\Website\HomeController::class,'resources'])->name('resource-list');
Route::get('gallery',[\App\Http\Controllers\Website\HomeController::class,'gallery'])->name('gallery');
Route::get('gallery/{slug}',[\App\Http\Controllers\Website\HomeController::class,'galleryDetail'])->name('gallery.detail');
Route::get('contact-us', [\App\Http\Controllers\Website\HomeController::class,'contact'])->name('contact');
Route::post('contact-us', [\App\Http\Controllers\Website\HomeController::class,'contactStore'])->name('contact.store');
Route::get('/AgmVerification/{slug}', action: [App\Http\Controllers\Website\HomeController::class, 'AgmVerification'])->name('agmVerification');

//login logout
//\Illuminate\Support\Facades\Auth::routes();
Route::get('dynamic/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('dynamic/login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->name('admin.login.submit');
Route::post('logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



Route::group(['prefix' => 'admin/', 'as' => 'admin.', 'middleware'=>['auth', 'role:super-dev|super-admin|admin']], function() {
    Route::group(['prefix' => 'dev/'], function() {
        Route::post('hrm/sort',            [\App\Http\Controllers\Admin\HrmController::class, 'sort'])
            ->name('hrm.sort');
        Route::resource('hrm',             \App\Http\Controllers\Admin\HrmController::class);
        Route::post('serviceCategory/sort',            [\App\Http\Controllers\Admin\ServiceCategoryController::class, 'sort'])
            ->name('serviceCategory.sort');
        Route::resource('serviceCategory',             \App\Http\Controllers\Admin\ServiceCategoryController::class);
        Route::post('resourceType/sort',            [\App\Http\Controllers\Admin\ResourceTypeController::class, 'sort'])
            ->name('resourceType.sort');
        Route::resource('resourceType',             \App\Http\Controllers\Admin\ResourceTypeController::class);
        Route::post('category/sort',            [\App\Http\Controllers\Admin\CategoryController::class, 'sort'])
            ->name('category.sort');
        Route::resource('category',             \App\Http\Controllers\Admin\CategoryController::class);
    });
    Route::get('dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
    Route::get('user/profile',[\App\Http\Controllers\Admin\UserController::class, 'profile'])->name('user.profile');
    Route::post('profileUpdate',[\App\Http\Controllers\Admin\UserController::class, 'profileUpdate'])->name('profileUpdate');
    Route::post('passwordUpdate',[\App\Http\Controllers\Admin\UserController::class, 'passwordUpdate'])->name('passwordUpdate');
    Route::resource('role',             \App\Http\Controllers\Admin\RoleController::class);
    Route::resource('permission',       \App\Http\Controllers\Admin\PermissionController::class);
    Route::resource('user',             \App\Http\Controllers\Admin\UserController::class);
    Route::post('cooperativeInformation/sort', [\App\Http\Controllers\Admin\CooperativeInformationController::class, 'sort'])
        ->name('cooperativeInformation.sort');
    Route::get('cooperativeInformation/download-demo', [\App\Http\Controllers\Admin\CooperativeInformationController::class, 'downloadDemoExcel'])
        ->name('cooperativeInformation.downloadDemo');
    Route::post('cooperativeInformation/upload-excel', [\App\Http\Controllers\Admin\CooperativeInformationController::class, 'uploadExcel'])
        ->name('cooperativeInformation.uploadExcel');
    Route::resource('cooperativeInformation', \App\Http\Controllers\Admin\CooperativeInformationController::class);

    Route::post('aboutUs/sort',            [\App\Http\Controllers\Admin\AboutUsController::class, 'sort'])
        ->name('aboutUs.sort');
    Route::resource('aboutUs',             \App\Http\Controllers\Admin\AboutUsController::class);

    Route::post('hrm/sort',            [\App\Http\Controllers\Admin\HrmController::class, 'sort'])
        ->name('hrm.sort');
    Route::resource('hrm',             \App\Http\Controllers\Admin\HrmController::class);

    Route::post('news/sort',            [\App\Http\Controllers\Admin\NewsController::class, 'sort'])
        ->name('news.sort');
    Route::resource('news',             \App\Http\Controllers\Admin\NewsController::class);

    Route::post('slider/sort',            [\App\Http\Controllers\Admin\SliderController::class, 'sort'])
        ->name('slider.sort');
    Route::resource('slider',             \App\Http\Controllers\Admin\SliderController::class);

    Route::post('notice/sort',            [\App\Http\Controllers\Admin\NoticeController::class, 'sort'])
        ->name('notice.sort');
    Route::resource('notice',             \App\Http\Controllers\Admin\NoticeController::class);

    Route::post('{slug}/service/sort',            [\App\Http\Controllers\Admin\ServiceController::class, 'sort'])
        ->name('service.sort');
    Route::resource('{slug}/service',             \App\Http\Controllers\Admin\ServiceController::class);

    Route::post('{slug}/team/sort',            [\App\Http\Controllers\Admin\TeamController::class, 'sort'])
        ->name('team.sort');
    Route::resource('{slug}/team',             \App\Http\Controllers\Admin\TeamController::class);

    Route::post('resource/sort',            [\App\Http\Controllers\Admin\ResourceController::class, 'sort'])
        ->name('resource.sort');
    Route::resource('resource',             \App\Http\Controllers\Admin\ResourceController::class);

    Route::post('gallery/sort',            [\App\Http\Controllers\Admin\GalleryController::class, 'sort'])
        ->name('gallery.sort');
    Route::resource('gallery',             \App\Http\Controllers\Admin\GalleryController::class);

    Route::post('district/{id}/toggle-autoselect', [\App\Http\Controllers\Admin\DistrictController::class, 'toggleAutoselect'])
        ->name('district.toggle-autoselect');
    Route::resource('district',             \App\Http\Controllers\Admin\DistrictController::class);
    Route::resource('municipality',             \App\Http\Controllers\Admin\MunicipalityController::class);
    Route::resource('statistic',             \App\Http\Controllers\Admin\StatisticController::class);
    Route::resource('contactForm',             \App\Http\Controllers\Admin\ContactFormController::class);
    
    Route::resource('agmForm',             App\Http\Controllers\Admin\AgmFormController::class);
     Route::get('suggestion', [\App\Http\Controllers\HomeController::class, 'suggestion'])->name('SuggestionForm.index');
    Route::delete('suggestion/{id}', [\App\Http\Controllers\HomeController::class, 'destroySuggestion'])->name('suggestionform.destroy');
    
    Route::post('faq/sort',            [\App\Http\Controllers\Admin\FAQController::class, 'sort'])
        ->name('faq.sort');
    Route::resource('faq',             \App\Http\Controllers\Admin\FAQController::class);

    Route::post('affiliation/sort',            [\App\Http\Controllers\Admin\AffiliationController::class, 'sort'])
        ->name('affiliation.sort');
    Route::resource('affiliation',             \App\Http\Controllers\Admin\AffiliationController::class);

    Route::post('successStory/sort',            [\App\Http\Controllers\Admin\SuccessStoryController::class, 'sort'])
        ->name('successStory.sort');
    Route::resource('successStory',             \App\Http\Controllers\Admin\SuccessStoryController::class);

    Route::post('menu/sort',            [\App\Http\Controllers\Admin\MenuController::class, 'sort'])
        ->name('menu.sort');
    Route::resource('menu',             \App\Http\Controllers\Admin\MenuController::class);

    Route::get('siteSetting',
        [\App\Http\Controllers\Admin\SiteSettingController::class,'index'])
        ->name('siteSetting.index');
    Route::post('siteSetting/update',
        [\App\Http\Controllers\Admin\SiteSettingController::class,'update'])
        ->name('siteSetting.update');
    Route::post('memberOrganization/sort',            [\App\Http\Controllers\Admin\MemberOrganizationController::class, 'sort'])
        ->name('memberOrganization.sort');
    Route::resource('memberOrganization',             \App\Http\Controllers\Admin\MemberOrganizationController::class);

    Route::get('downloadPassportFolder', action: [AgmFormController::class, 'downloadPassportFolder'])->name('downloadPassportFolder');
    Route::get('downloadQRFolder', action: [AgmFormController::class, 'downloadQRFolder'])->name('downloadQRFolder');
    Route::get('downloadAgmExcel', [AgmFormController::class, 'exportToExcel'])->name('downloadAgmExcel');
    Route::post('approve/{id}', [AgmFormController::class, 'approve'])->name('AgmApprove');

    // ---- Admin Event Routes ----
    Route::resource('event', EventController::class);
    Route::get('event/{id}/bookings', [EventController::class, 'bookings'])->name('event.bookings');
    Route::get('event/{id}/participants', [EventController::class, 'participants'])->name('event.participants');
    Route::get('event/{id}/bookings/bulk-cards', [EventController::class, 'printBulkCards'])->name('event.bookings.bulk-cards');
    Route::post('event/{id}/bookings/store', [EventController::class, 'storeBooking'])->name('event.bookings.store');
    Route::post('event/{id}/bookings/send-bulk-message', [EventController::class, 'sendBulkMessage'])->name('event.bookings.send-bulk-message');
    Route::get('event/booking/{id}/show', [EventController::class, 'showBooking'])->name('event.booking.show');
    Route::get('event/booking/{id}/cards', [EventController::class, 'printCards'])->name('event.booking.cards');
    Route::post('event/booking/{id}/approve', [EventController::class, 'approveBooking'])->name('event.booking.approve');
    Route::post('event/booking/{id}/update', [EventController::class, 'updateBooking'])->name('event.booking.update');
    Route::post('event/booking/{id}/reject', [EventController::class, 'rejectBooking'])->name('event.booking.reject');
    Route::post('event/booking/{id}/send-mail', [EventController::class, 'sendEventDetailsMail'])->name('event.booking.send-mail');
    Route::post('event/booking/{id}/mark-present', [EventController::class, 'markPresent'])->name('event.booking.mark-present');
    Route::post('event/booking/participant/{id}/mark-present', [EventController::class, 'markParticipantPresent'])->name('event.booking.participant.mark-present');
    Route::post('event/booking/participant/{id}/update', [EventController::class, 'updateParticipant'])->name('event.booking.participant.update');
    // Specific certificate routes MUST come before wildcard {id} routes
    Route::post('event/certificate/send', [EventController::class, 'sendCertificates'])->name('event.certificate.send');
    Route::post('event/certificate/bulk-download', [EventController::class, 'bulkDownloadCertificates'])->name('event.certificate.bulk-download');
    Route::get('event/{id}/certificate', [EventController::class, 'certificateSettings'])->name('event.certificate.settings');
    Route::post('event/{id}/certificate', [EventController::class, 'updateCertificateSettings'])->name('event.certificate.update');
    Route::get('event/{id}/certificate/download/{participant}', [EventController::class, 'downloadCertificate'])->name('event.certificate.download');

     
    Route::resource('paymentQr', PaymentQrController::class);

  
    Route::get('otp-requests', [\App\Http\Controllers\Admin\OtpRequestController::class, 'index'])->name('otp-requests.index');
    Route::post('otp-requests/send', [\App\Http\Controllers\Admin\OtpRequestController::class, 'adminSendOtp'])->name('otp-requests.send-otp');
    Route::post('otp-requests/register', [\App\Http\Controllers\Admin\OtpRequestController::class, 'adminVerifyAndRegister'])->name('otp-requests.register');
});
