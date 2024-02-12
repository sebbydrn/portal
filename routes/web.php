<?php

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

Route::get('/', 'LandingPageController@index');

Route::get('about_us', 'AboutUsController@rsis');
Route::get('about_us/rsis', 'AboutUsController@rsis');
Route::get('about_us/objectives', 'AboutUsController@objectives');
Route::get('about_us/implementers', 'AboutUsController@implementers');
Route::get('about_us/partners', 'AboutUsController@partners');

/* CONTACT US ROUTES */
Route::get('contact_us', 'ContactUsController@index')->name('contact_us.index');
Route::post('contact_us', 'ContactUsController@store')->name('contact_us.store');
/* END OF CONTACT US ROUTES */

Route::get('links', 'LinkController@index');

Route::get('website_terms_and_conditions', 'PrivacyNoticeController@index');

Route::get('sitemap', 'SiteMapController@index');

Route::get('helpdesk', 'HelpdeskController@index');

// Route::get('dashboard', 'DashboardController@index');

Route::get('downloads', 'DownloadsController@index');
Route::get('/downloads/download','DownloadsController@download')->name('downloads.download');

Auth::routes();

Route::get('register/success', 'Auth\RegisterController@success');

/* GET REGION AND MUNICIPALITIES FOR REGISTRATION FORM */
Route::post('register/regions', 'Auth\RegisterController@region_code')->name('register.regions.region_code');
Route::post('register/municipalities', 'Auth\RegisterController@municipalities')->name('register.municipalities');

/* ACTIVATE/ADD PASSWORD FOR NEW ACCOUNT */
Route::get('activate_account/{link}', 'ActivateAccountController@index')->name('activate_account.index');
Route::put('activate_account/{link}', 'ActivateAccountController@update')->name('activate_account.update');

/* CHECK IF USER IS LOGGED IN */
Route::get('check_logged_in', 'LockController@check_logged_in')->name('check_logged_in');

// EMAIL FOR DATA COMPLIANCE
Route::get('monitoring/data_compliance', 'Monitoring\DataComplianceController@index')->name('monitoring.data_compliance');

Route::get('monitoring/seed_inventory', 'Monitoring\SeedInventoryController@index')->name('monitoring.seed_inventory');

// link for seed inventory per lot with date of update
Route::get('seed_inventory_updates', 'Monitoring\SeedInventoryController@seed_inventory_updates')->name('monitoring.seed_inventory_updates');

Route::get('dashboard', 'Dashboard3Controller@index')->name('dashboard3.index');
Route::get('dashboard/filter/{year}/{sem}', 'Dashboard3Controller@filter')->name('dashboard3.filter');

Route::get('resources/video_guides', 'ResourcesController@video_guides')->name('resources.video_guides');

Route::group(['middleware' => ['auth']], function() {
	/* LOCKSCREEN */
    Route::post('unlock', 'LockController@unlock')->name('unlock');

    /* PROFILE ROUTES */
	Route::get('profile', 'ProfileController@index')->name('profile.index');
	Route::get('profile/password', 'ProfileController@password');
	Route::get('profile/portal', 'ProfileController@portal');
	Route::get('profile/analytics', 'ProfileController@analytics');
	Route::get('profile/settings', 'ProfileController@settings');
	Route::get('profile/activity_log/seedsale', 'ProfileController@seedsale');
	Route::get('profile/activity_log/logaccess', 'ProfileController@logaccess');
	Route::get('profile/activity_log/logaction', 'ProfileController@logaction');
	Route::get('profile/{id}/edit','ProfileController@edit')->name('profile.edit');
	Route::patch('profile/{id}','ProfileController@update')->name('profile.update');

	Route::post('profile/addAvatar','ProfileController@addAvatar')->name('profile.avatar');
	Route::patch('password/updatePassword','ProfileController@updatePassword')->name('profile.updatePassword');

	Route::post('users/regions', 'ProfileController@region_code')->name('users.regions.region_code');
	Route::post('users/municipalities', 'ProfileController@municipalities_code')->name('users.municipalities');

	Route::post('profile/activity_log/provinces','ProfileController@getProvinces');
	Route::post('profile/activity_log/municipalities','ProfileController@getMunicipalities');

	Route::post('profile/activity_log/seedsale/datatable','ProfileController@seedLogDatatable')->name('seedsale.datatable');
	Route::post('profile/activity_log/logaccess/datatable','ProfileController@logAccessDatatable')->name('logaccess.datatable');
	Route::post('profile/activity_log/logaction/datatable','ProfileController@logActionDatatable')->name('logaction.datatable');

	Route::get('seedsaleReport','ReportController@seedsaleExcel')->name('seedsale.excel');
	Route::get('logaccessReport','ReportController@logaccessExcel')->name('logaccess.excel');
	Route::get('logactionReport','ReportController@logactionExcel')->name('logaction.excel');
	/* END OF PROFILE ROUTES */

	/* SEED PRODUCER DASHBOARD ROUTES */

	// Seed seed production volume estimates chart
    Route::post('dashboard/production_volume', 'DashboardController@production_volume')->name('production_volume');
    // Geotagged seed production area
    Route::post('dashboard/production_area', 'DashboardController@production_area')->name('production_area');
    // Drilldown for seed production volume estimates bar chart
    Route::post('dashboard/production_volume_dd', 'DashboardController@production_volume_dd')->name('production_volume_dd');

    Route::get('dashboard2', 'Dashboard2Controller@index')->name('dashboard2.index');
    Route::get('dashboard2/growApp', 'Dashboard2Controller@growApp')->name('dashboard2.growApp');
    Route::get('dashboard2/growApp_area', 'Dashboard2Controller@growApp_area')->name('dashboard2.growApp_area');
    Route::post('dashboard2/growApp_area_datatable', 'Dashboard2Controller@growApp_area_datatable')->name('dashboard2.growApp_area_datatable');
    Route::get('dashboard2/prelim_area', 'Dashboard2Controller@prelim_area')->name('dashboard2.prelim_area');
    Route::post('dashboard2/prelim_area_datatable', 'Dashboard2Controller@prelim_area_datatable')->name('dashboard2.prelim_area_datatable');
    Route::get('dashboard2/finalinsp_area', 'Dashboard2Controller@finalinsp_area')->name('dashboard2.finalinsp_area');
    Route::post('dashboard2/finalinsp_area_datatable', 'Dashboard2Controller@finalinsp_area_datatable')->name('dashboard2.finalinsp_area_datatable');
    Route::get('dashboard2/seed_growers', 'Dashboard2Controller@seed_growers')->name('dashboard2.seed_growers');
    Route::post('dashboard2/getProvinces', 'Dashboard2Controller@getProvinces')->name('dashboard2.getProvinces');
    Route::post('dashboard2/getMunicipalities', 'Dashboard2Controller@getMunicipalities')->name('dashboard2.getMunicipalities');
    Route::post('dashboard2/seed_growers/provinces', 'Dashboard2Controller@seed_growers_prov')->name('dashboard2.seed_growers_prov');
    Route::post('dashboard2/seed_growers/regions', 'Dashboard2Controller@seed_growers_reg')->name('dashboard2.seed_growers_reg');

    Route::get('dashboard3/sim', 'Dashboard3Controller@simulation')->name('dashboard3.simulation');

    // Route::get('dashboard3', 'Dashboard3Controller@index')->name('dashboard3.index');
    Route::get('dashboard3/sales', 'Dashboard3Controller@sales')->name('dashboard3.sales');
    Route::get('dashboard3/seed_production', 'Dashboard3Controller@seed_production')->name('dashboard3.seed_production');

    // DATA MONITORING
    Route::get('monitoring/grow_app', 'Monitoring\GrowAppController@index')->name('monitoring.grow_app');
    Route::get('monitoring/seed_production_planner', 'Monitoring\SeedProductionPlannerController@index')->name('monitoring.seed_production_planner');
});

