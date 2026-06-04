<?php
use Laravel\Socialite\Facades\Socialite;
use Spatie\Honeypot\ProtectAgainstSpam;
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

//Route::get('/', function () {
//    return view('welcome');
//});
//Auth::routes();
//Route::get('/home', 'HomeController@index');
//
//Route::get('/', 'PostController@index')->name('home');

Route::get('/', '\App\Http\Controllers\IndexController@index');
//Route::get('/home', 'IndexController@home');
Route::get('/generatesitemap', '\App\Http\Controllers\IndexController@generatesitemap');


Route::get('/sync-bg-images', '\App\Http\Controllers\SyncController@index');
Route::get('/sync-listing-to-center', '\App\Http\Controllers\SyncController@syncListingToCentre');
Route::get('/sync-bb-images', '\App\Http\Controllers\SyncBoatController@index');

Route::get('/chatgpt', '\App\Http\Controllers\IndexController@chatgpt');
Route::post('/chatgpt', '\App\Http\Controllers\IndexController@chatgpt');

Route::get('/test-email', '\App\Http\Controllers\EmailController@testemail');

Route::get('/festivals', function() {
    return View::make('festivals');
});
Route::get('list-your-retreats-get-more-sales', '\App\Http\Controllers\ExperienceController@pro_listing');
Route::post('/send_pro_listing_contact_us_email', '\App\Http\Controllers\EmailController@send_pro_listing_contact_us_email')->middleware(ProtectAgainstSpam::class);

Route::get('/category/{cat}', '\App\Http\Controllers\CategoryController@index');
Route::get('/category/{cat}/{subcat}', '\App\Http\Controllers\CategoryController@index');

Route::get('/location/{dest}', '\App\Http\Controllers\LocationController@index');
Route::get('/location/{dest}/{subdest}', '\App\Http\Controllers\LocationController@index');

Route::get('/experiences', '\App\Http\Controllers\ExperienceController@index');
Route::get('/experiences/loadDataAjax', '\App\Http\Controllers\ExperienceController@loadDataAjax');
Route::get('/experience', '\App\Http\Controllers\ExperienceController@index');
Route::get('/experience/loadDataAjax', '\App\Http\Controllers\ExperienceController@loadDataAjax');
Route::get('/experience/{slug}', '\App\Http\Controllers\ExperienceController@index');
Route::get('/experience-inquiry/{slug}', '\App\Http\Controllers\ExperienceController@inquiry');
Route::any('/search-experiences', '\App\Http\Controllers\ExperienceController@search');
Route::any('/search-auto-experiences', '\App\Http\Controllers\ExperienceController@search_experience');
Route::post('/get_ajax_exp_accomodation', '\App\Http\Controllers\ExperienceController@get_ajax_exp_accomodation');
Route::post('/store-inquiry', '\App\Http\Controllers\ExperienceController@store_inquiry');

// customer inquiry
Route::get('/customerinquiry/{conversationid}', '\App\Http\Controllers\CustomerController@inquiry');
Route::post('/savemessage', '\App\Http\Controllers\CustomerController@savemessage');

Route::post('/redirect-to-portal', '\App\Http\Controllers\ExperienceController@redirect_to_portal');

Route::get('/centers', '\App\Http\Controllers\CenterController@index');
Route::get('/center/{slug}', '\App\Http\Controllers\CenterController@index');
Route::post('/get_ajax_filter_values', '\App\Http\Controllers\CenterController@get_ajax_filter_values');
Route::post('/get_booking_price', '\App\Http\Controllers\CenterController@get_booking_price');

Route::get('/teachers', '\App\Http\Controllers\TeacherController@index');
Route::get('/teacher/{slug}', '\App\Http\Controllers\TeacherController@index');

Route::get('/blog', '\App\Http\Controllers\BlogController@index');
Route::get('/blog/{slug}', '\App\Http\Controllers\BlogController@index');
Route::get('/blog-list', '\App\Http\Controllers\BlogController@blog_list');
Route::get('/blog-list/{catgory}', '\App\Http\Controllers\BlogController@blog_list');
Route::get('click-ads', '\App\Http\Controllers\BlogController@click_ads');

Route::any('/reservation', '\App\Http\Controllers\ReservationController@index');
Route::post('/reservation/store', '\App\Http\Controllers\ReservationController@store');
Route::post('/user/check-email-exist', '\App\Http\Controllers\UserController@check_email_exist');
Route::get('reservation/success', '\App\Http\Controllers\ReservationController@success');

Route::get('/payment', '\App\Http\Controllers\PaymentController@index');
Route::post('/payment/process', '\App\Http\Controllers\PaymentController@process');
Route::post('/payment/response', '\App\Http\Controllers\PaymentController@response');
Route::any('/payment/success', '\App\Http\Controllers\PaymentController@success');
Route::any('/payment/cancel', '\App\Http\Controllers\PaymentController@cancel');

Route::get('deals', '\App\Http\Controllers\DealController@index');
Route::get('best-deals', '\App\Http\Controllers\DealController@index');
Route::get('/deal/{slug}', '\App\Http\Controllers\DealController@index');

Route::group(['middleware' => ['auth']], function() {

    // My Account
    Route::get('myaccount', '\App\Http\Controllers\BookingController@index');
    Route::get('booking', '\App\Http\Controllers\BookingController@booking');
    Route::get('booking/{id}', '\App\Http\Controllers\BookingController@booking');

    /* User Routes */
    Route::post('/user/update-profile', '\App\Http\Controllers\UserController@update_profile');
    Route::post('/user/delete_image', '\App\Http\Controllers\UserController@delete_image');
});

Route::get('/register', '\App\Http\Controllers\Admin\Auth\RegisterController@showFrontRegistrationForm')->name('register');
Route::post('/login', '\App\Http\Controllers\Admin\Auth\LoginController@login');
Route::get('/login', '\App\Http\Controllers\Admin\Auth\LoginController@showFrontLoginForm')->name('login');
Route::any('/logout', '\App\Http\Controllers\Admin\Auth\LoginController@logout')->name('logout');

Route::resource('roles', '\App\Http\Controllers\RoleController');
Route::resource('permissions', '\App\Http\Controllers\PermissionController');
Route::resource('posts', '\App\Http\Controllers\PostController');

// Admin Routes
Route::get('bbadmin/login', '\App\Http\Controllers\Admin\Auth\LoginController@showLoginForm')->name('login');
Route::post('bbadmin/login', '\App\Http\Controllers\Admin\Auth\LoginController@login');
Route::get('bbadmin/logout', '\App\Http\Controllers\Admin\Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('bbadmin/register', '\App\Http\Controllers\Admin\Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('bbadmin/register', '\App\Http\Controllers\Admin\Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', '\App\Http\Controllers\Admin\Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('password/email', '\App\Http\Controllers\Admin\Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset/{token}', '\App\Http\Controllers\Admin\Auth\ResetPasswordController@showResetForm');
Route::post('password/reset', '\App\Http\Controllers\Admin\Auth\ResetPasswordController@reset');

Route::get('/about-us', function() {
    return View::make('aboutus');
});

Route::get('/contact-us', function() {
    return View::make('contactus');
});
Route::post('/send-register-your-business-email', '\App\Http\Controllers\EmailController@send_register_your_business_email')->middleware(ProtectAgainstSpam::class);
Route::post('/send-contact-us-email', '\App\Http\Controllers\EmailController@send_contact_us_email')->middleware(ProtectAgainstSpam::class);
Route::post('/send-inquiry-email', '\App\Http\Controllers\EmailController@send_inquiry_email')->middleware(ProtectAgainstSpam::class);
Route::post('/api/save-quick-lead', '\App\Http\Controllers\EmailController@save_quick_lead')->name('save.quick-lead');

Route::post('send-check-availability', '\App\Http\Controllers\EmailController@send_check_availability_email');
Route::get('check-availability-response', '\App\Http\Controllers\EmailController@check_availability_response')->name('check-availability.response');

Route::get('/help', function() {
    return View::make('help');
});

Route::get('/privacy-policy', function() {
    return View::make('privacy-policy');
});

Route::get('/cookie-policy', function() {
    return View::make('cookie-policy');
});

Route::get('/terms-and-conditions', function() {
    return View::make('terms-and-conditions');
});

Route::get('ayurveda-kerala', function() {
    return View::make('offers');
});
Route::post('store-subscription', '\App\Http\Controllers\EmailController@store_subscription');
Route::post('store-blog-subscription', '\App\Http\Controllers\EmailController@store_blog_subscription');
Route::post('store-chat', '\App\Http\Controllers\EmailController@store_chat')->middleware(ProtectAgainstSpam::class);
Route::post('send-request-call-back', '\App\Http\Controllers\EmailController@send_request_call_back')->middleware(ProtectAgainstSpam::class);
Route::post('send-request-call-back-blog', '\App\Http\Controllers\EmailController@send_request_call_back_blog')->middleware(ProtectAgainstSpam::class);
//Route::middleware(['cors'])->group(function () {
    Route::get('bbadmin/experiences/getexperiences', '\App\Http\Controllers\Admin\DealsController@getexperiences');
//});
Route::group(['prefix' => 'bbadmin', 'middleware' => ['auth', 'isAdmin'], 'namespace' => 'App\Http\Controllers'], function() {
    Route::get('/', 'Admin\IndexController@index');

    // Users Management
    Route::resource('/users', 'Admin\UserController');
    Route::get('/users/invitation/{uid}', 'Admin\UserController@invitation');
    Route::post('/users/store', 'Admin\UserController@store');

    // Category Management
    Route::get('/category', 'Admin\CategoryController@index');
    Route::get('/category/create', 'Admin\CategoryController@create');
    Route::get('/category/edit/{id}', 'Admin\CategoryController@edit');
    Route::post('/category/store', 'Admin\CategoryController@store');
    Route::post('/category/destroy', 'Admin\CategoryController@destroy');
    Route::post('/category/delete_image', 'Admin\CategoryController@delete_image');
    Route::post('/category/delete_banner_image', 'Admin\CategoryController@delete_banner_image');
    
    // Deal Management
    Route::get('deals', 'Admin\DealsController@index');
    Route::get('deal/create', 'Admin\DealsController@create');
    Route::get('deal/edit/{id}', 'Admin\DealsController@edit');
    Route::post('deal/store', 'Admin\DealsController@store');
    Route::post('deal/destroy', 'Admin\DealsController@destroy');
    Route::post('deal/delete_image', 'Admin\DealsController@delete_image');

    // Certificates Management
    Route::get('/certificates', 'Admin\CertificatesController@index');
    Route::get('/certificates/create', 'Admin\CertificatesController@create');
    Route::get('/certificates/edit/{id}', 'Admin\CertificatesController@edit');
    Route::post('/certificates/store', 'Admin\CertificatesController@store');
    Route::post('/certificates/destroy', 'Admin\CertificatesController@destroy');
    Route::post('/certificates/delete_image', 'Admin\CertificatesController@delete_image');

    // Teachers Management
    Route::get('/teachers', 'Admin\TeachersController@index');
    Route::get('/teachers/create', 'Admin\TeachersController@create');
    Route::get('/teachers/edit/{id}', 'Admin\TeachersController@edit');
    Route::post('/teachers/store', 'Admin\TeachersController@store');
    Route::post('/teachers/destroy', 'Admin\TeachersController@destroy');
    Route::post('/teachers/delete_image', 'Admin\TeachersController@delete_image');
    Route::post('/teachers/upload_gallery_image', 'Admin\TeachersController@upload_gallery_image');
    Route::post('/teachers/delete_gallery_image', 'Admin\TeachersController@delete_gallery_image');

    // Centers Management
    
    Route::get('centers/upload', 'Admin\CentersController@upload');
    Route::post('centers/store-upload', 'Admin\CentersController@storeUpload');
    
    Route::get('/centers', 'Admin\CentersController@index');
    Route::get('/centers/create', 'Admin\CentersController@create');
    Route::get('/centers/edit/{id}', 'Admin\CentersController@edit');
    Route::post('/centers/store', 'Admin\CentersController@store');
    Route::post('/centers/destroy', 'Admin\CentersController@destroy');
    Route::post('/centers/delete_image', 'Admin\CentersController@delete_image');
    Route::post('/centers/upload_gallery_image', 'Admin\CentersController@upload_gallery_image');
    Route::post('/centers/delete_gallery_image', 'Admin\CentersController@delete_gallery_image');
    Route::post('/centers/get_center_accomodation', 'Admin\CentersController@get_center_accomodation');
    Route::post('/centers/get_center_teachers', 'Admin\CentersController@get_center_teachers');
    Route::post('/centers/delete_accomodation_image', 'Admin\CentersController@delete_accomodation_image');
    Route::get('centers/getcenters', 'Admin\CentersController@getcenters');

    // Accomodation Management
    Route::get('/accomodations', 'Admin\AccomodationController@index');
    Route::get('/accomodation/create', 'Admin\AccomodationController@create');
    Route::get('/accomodation/edit/{id}', 'Admin\AccomodationController@edit');
    Route::post('/accomodation/store', 'Admin\AccomodationController@store');
    Route::post('/accomodation/destroy', 'Admin\AccomodationController@destroy');
    Route::post('/accomodation/delete_image', 'Admin\AccomodationController@delete_image');
    Route::post('/accomodation/upload_gallery_image', 'Admin\AccomodationController@upload_gallery_image');
    Route::post('/accomodation/delete_gallery_image', 'Admin\AccomodationController@delete_gallery_image');

    // Commission Management
    Route::get('/commissions', 'Admin\CommissionController@index');
    Route::get('/commission/create', 'Admin\CommissionController@create');
    Route::get('/commission/edit/{id}', 'Admin\CommissionController@edit');
    Route::post('/commission/store', 'Admin\CommissionController@store');
    Route::post('/commission/destroy', 'Admin\CommissionController@destroy');

    // Experiences Management
    
    Route::get('experiences/upload', 'Admin\ExperiencesController@upload');
    Route::post('experiences/store-upload', 'Admin\ExperiencesController@storeUpload');
    
    Route::get('/experiences', 'Admin\ExperiencesController@index');
    Route::get('/experiences/create', 'Admin\ExperiencesController@create');
    Route::get('/experiences/edit/{id}', 'Admin\ExperiencesController@edit');
    Route::get('/experiences/clone/{id}', 'Admin\ExperiencesController@clone_exp');
    Route::post('/experiences/store', 'Admin\ExperiencesController@store');
    Route::post('/experiences/destroy', 'Admin\ExperiencesController@destroy');
    Route::post('/experiences/delete_thumbnail_image', 'Admin\ExperiencesController@delete_thumbnail_image');
    Route::post('/experiences/delete_image', 'Admin\ExperiencesController@delete_image');
    Route::post('/experiences/upload_gallery_image', 'Admin\ExperiencesController@upload_gallery_image');
    Route::post('/experiences/delete_gallery_image', 'Admin\ExperiencesController@delete_gallery_image');
    Route::post('/experiences/upload_accomodation_gallery_image', 'Admin\ExperiencesController@upload_accomodation_gallery_image');
    Route::post('/experiences/delete_accomodation_gallery_image', 'Admin\ExperiencesController@delete_accomodation_gallery_image');
    Route::post('/experiences/delete_food_image', 'Admin\ExperiencesController@delete_food_image');
    Route::post('/experiences/delete_food_gallery_image', 'Admin\ExperiencesController@delete_food_gallery_image');
    
    // Leads Management
    Route::get('leads', 'Admin\LeadsController@index');
    Route::get('lead/details/{id}', 'Admin\LeadsController@details');
    Route::get('lead/create', 'Admin\LeadsController@create');
    Route::post('lead/store', 'Admin\LeadsController@store');

    // Blog Management
    Route::get('/blogs', 'Admin\BlogController@index');
    Route::get('/blog/create', 'Admin\BlogController@create');
    Route::get('/blog/edit/{id}', 'Admin\BlogController@edit');
    Route::post('/blog/store', 'Admin\BlogController@store');
    Route::post('/blog/destroy', 'Admin\BlogController@destroy');
    Route::post('/blog/delete_image', 'Admin\BlogController@delete_image');
    Route::post('/blog/upload_gallery_image', 'Admin\BlogController@upload_gallery_image');
    Route::post('/blog/delete_gallery_image', 'Admin\BlogController@delete_gallery_image');

    // Booking Management
    Route::get('/bookings', 'Admin\BookingsController@index');
    Route::get('/booking/create', 'Admin\BookingsController@create');
    Route::get('/booking/edit/{id}', 'Admin\BookingsController@edit');
    Route::post('/booking/store', 'Admin\BookingsController@store');
    Route::post('/booking/destroy', 'Admin\BookingsController@destroy');

    Route::get('/booking/experience-details', [App\Http\Controllers\Admin\BookingsController::class, 'getExperienceDetails']);

    // Export Management
    Route::get('export/', 'Admin\ExportController@index');
    Route::get('export/experience-packages', 'Admin\ExportController@experience_packages');
    Route::get('report/experiences', 'Admin\ReportController@experiences');
    Route::post('report/generate-experience-report', 'Admin\ReportController@generate_experience_report');
    Route::get('report/teachers', 'Admin\ReportController@teachers');
    Route::get('report/generate-teachers-report', 'Admin\ReportController@generate_teachers_report');

    // Marquee Management
    Route::get('/marquee/', 'Admin\MarqueeController@index');
    Route::post('/marquee/update', 'Admin\MarqueeController@update_marquee');
    
    // Upload Management
    Route::get('/upload/', 'Admin\UploadController@index');
    Route::post('/upload/store-sitemap', 'Admin\UploadController@store_sitemap');
    
    // Center Onboard Management
    Route::get('/centre-onboard', 'Admin\CentreOnboardController@index');
    Route::post('/centre-onboard/import', 'Admin\CentreOnboardController@import');
    Route::get('/centre-onboard/create', 'Admin\CentreOnboardController@create');
    Route::get('/centre-onboard/edit/{id}', 'Admin\CentreOnboardController@edit');
    Route::post('/centre-onboard/store', 'Admin\CentreOnboardController@store');
    Route::post('/centre-onboard/destroy', 'Admin\CentreOnboardController@destroy');
    Route::any('/centre-onboard/config-email-template', 'Admin\CentreOnboardController@config_email_template');

    // Adverts Management
    Route::get('/adverts', 'Admin\AdvertController@index');
    Route::get('/advert/create', 'Admin\AdvertController@create');
    Route::get('/advert/edit/{id}', 'Admin\AdvertController@edit');
    Route::post('/advert/store', 'Admin\AdvertController@store');
    Route::post('/advert/destroy', 'Admin\AdvertController@destroy');
    Route::post('/advert/delete_image', 'Admin\AdvertController@delete_image');
    
    
    Route::get('list-azure-files', function() {

        $path = "/experiences/2023/06/10/";
        // Get the Larvel disk for Azure
        $disk = \Storage::disk('azure');
        
        //$disk->exists("job-manager-uploads/gallery_images/2015/09/cherai_beachresorssst_10.jpg");
        
        // List files in the container path
        $files = $disk->listContents($path);
        
        // create an array to store the names, sizes and last modified date
        $list = array();

        // Process each filename and get the size and last modified date
        foreach($files as $file) {
                $size = $disk->size($file);

                $modified = $disk->lastModified($file);
                $modified = date("Y-m-d H:i:s", $modified);

                $filename = "$path/$file";

                $item = array(
                        'name' => $filename,
                        'size' => $size,
                        'modified' => $modified,
                );

                array_push($list, $item);
        }

        $results = json_encode($list, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        return response($results)->header('content-type', 'application/json');
    });
});

Route::get('/generate-sitemap', function() {
    $exitCode = Artisan::call('sitemap:generate');
});

Route::get('/payment_new', '\App\Http\Controllers\PaymentController@index_new');
Route::post('payment-new-stor', '\App\Http\Controllers\PaymentController@payment')->name('payment-new-stor');




Route::get('/auth/redirect', function () { return Socialite::driver('github')->redirect(); });

Route::get('/auth/callback', function () {$user = Socialite::driver('github')->user();
    // $user->token
});

Route::get('send-sample-mail', function(){
    Illuminate\Support\Facades\Mail::raw('This is a test email!', function ($message) {
        $message->to('ammarjinia@gmail.com')->subject('Balanceboat - Sample Mail');
    });
});
Route::get('send-sample-wa', function(){
    $waData = array(
        "name" => "Kunal",
        "phone" => "+91-9409279450",
        "email" => "kunal@gmail.com",
        "notes" => "This is test message from balanceboat.com sample"
    );
    (new \App\Services\WaService())->sendMessage($waData);
});


route::get('test-inquiry', function() {    
    //(new \App\Services\ActiveCampaignService())->trackCartActivity("kunal@balanceboat.com", "Kunal Singh", "https://balanceboat.com/experience/15-days-ayurvedic-panchakarma-stress-management-package-kovalam-kerala-india", 'send_request_call_back');

    $exp_id = 9611;
    $experience = \App\Experiences::select("id", "name", "slug", "center_id")->where("id", @$exp_id)->first();
    $dest = $experience->destinations?->pluck('name')->join(', ') ?? '';

    dd($dest);

    $eventData = array(
        "firstName" => "Ammar",
        "lastName" => "Jinia",
        "phone" => "919428546795",
        "source" => "https://balanceboat.com/experience/15-days-ayurvedic-panchakarma-stress-management-package-kovalam-kerala-india"
    );
    (new \App\Services\ActiveCampaignService())->trackCartActivity("ammarjinia@gmail.com", $eventData, "Lead: New");

});