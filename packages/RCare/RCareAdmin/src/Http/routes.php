<?php

/*
|--------------------------------------------------------------------------
| RCare / RCareAdmin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Route::middleware('auth:api')->get("/", function () {
//      return view("RCareAdmin::home");
// })->name("home");
Route::get("/", "RCare\RCareAdmin\Http\Controllers\DashboardController@test")->name("home");
Route::get("/logout", "RCare\RCareAdmin\Http\Controllers\Auth\LoginController@logout")->name("logout");

// Guest only routes
Route::middleware(["guest", "web"])->group(function () {
    // Route::get("/login", "RCare\RCareAdmin\Http\Controllers\Auth\LoginController@index")->name("login");
    Route::get("/rcare-login", "RCare\RCareAdmin\AdminPackages\Login\src\Http\Controllers\Auth\LoginController@index")->name("rcare-login");
    // Route::get("/forgot-password", "RCare\RCareAdmin\Http\Controllers\Auth\LoginController@forgotPassword")->name("login.forgot_password");
    // Route::get("/reset-password/{token}", "RCare\RCareAdmin\Http\Controllers\Auth\LoginController@resetPassword")->name("login.reset_password");
    Route::post("/login", "RCare\RCareAdmin\Http\Controllers\Auth\LoginController@authenticate");
    // Route::post("/forgot-password", "RCare\RCareAdmin\Http\Controllers\Auth\LoginController@requestPasswordReset");
    // Route::post("/reset-password/{token}", "RCare\RCareAdmin\Http\Controllers\Auth\LoginController@setPassword");
});

// Authenticated user only routes
// Route::middleware(["auth", "web"])->group(function () {
//     //dashboard
//     Route::get('/dashboard', 'RCare\RCareAdmin\Http\Controllers\DashboardController@index')->name('dashboard');
// });



// ///TO BE DELETED
// Route::view('dashboard/dashboard1', 'Theme::dashboard.dashboard')->name('dashboard_version_1');
// Route::view('dashboard/dashboard3', 'Theme::dashboard.dashboard')->name('dashboard_version_3');
// Route::view('dashboard/dashboard4', 'Theme::dashboard.dashboard')->name('dashboard_version_4');
// Route::view('uikits/alerts', 'uiKits.alerts')->name('alerts');
// Route::view('uikits/accordion', 'uiKits.accordion')->name('accordion');
// Route::view('uikits/buttons', 'uiKits.buttons')->name('buttons');
// Route::view('uikits/badges', 'uiKits.badges')->name('badges');
// Route::view('uikits/bootstrap-tab', 'uiKits.bootstrap-tab')->name('bootstrap-tab');
// Route::view('uikits/carousel', 'uiKits.carousel')->name('carousel');
// Route::view('uikits/collapsible', 'uiKits.collapsible')->name('collapsible');
// Route::view('uikits/lists', 'uiKits.lists')->name('lists');
// Route::view('uikits/pagination', 'uiKits.pagination')->name('pagination');
// Route::view('uikits/popover', 'uiKits.popover')->name('popover');
// Route::view('uikits/progressbar', 'uiKits.progressbar')->name('progressbar');
// Route::view('uikits/tables', 'uiKits.tables')->name('tables');
// Route::view('uikits/tabs', 'uiKits.tabs')->name('tabs');
// Route::view('uikits/tooltip', 'uiKits.tooltip')->name('tooltip');
// Route::view('uikits/modals', 'uiKits.modals')->name('modals');
// Route::view('uikits/NoUislider', 'uiKits.NoUislider')->name('NoUislider');
// Route::view('uikits/cards', 'uiKits.cards')->name('cards');
// Route::view('uikits/cards-metrics', 'uiKits.cards-metrics')->name('cards-metrics');
// Route::view('uikits/typography', 'uiKits.typography')->name('typography');

// // extra kits
// Route::view('extrakits/dropDown', 'extraKits.dropDown')->name('dropDown');
// Route::view('extrakits/imageCroper', 'extraKits.imageCroper')->name('imageCroper');
// Route::view('extrakits/loader', 'extraKits.loader')->name('loader');
// Route::view('extrakits/laddaButton', 'extraKits.laddaButton')->name('laddaButton');
// Route::view('extrakits/toastr', 'extraKits.toastr')->name('toastr');
// Route::view('extrakits/sweetAlert', 'extraKits.sweetAlert')->name('sweetAlert');
// Route::view('extrakits/tour', 'extraKits.tour')->name('tour');
// Route::view('extrakits/upload', 'extraKits.upload')->name('upload');


// // Apps
// Route::view('apps/invoice', 'apps.invoice')->name('invoice');
// Route::view('apps/inbox', 'apps.inbox')->name('inbox');
// Route::view('apps/chat', 'apps.chat')->name('chat');
// Route::view('apps/calendar', 'apps.calendar')->name('calendar');
// Route::view('apps/task-manager-list', 'apps.task-manager-list')->name('task-manager-list');
// Route::view('apps/task-manager', 'apps.task-manager')->name('task-manager');
// Route::view('apps/toDo', 'apps.toDo')->name('toDo');
// Route::view('apps/ecommerce/products', 'apps.ecommerce.products')->name('ecommerce-products');
// Route::view('apps/ecommerce/product-details', 'apps.ecommerce.product-details')->name('ecommerce-product-details');
// Route::view('apps/ecommerce/cart', 'apps.ecommerce.cart')->name('ecommerce-cart');
// Route::view('apps/ecommerce/checkout', 'apps.ecommerce.checkout')->name('ecommerce-checkout');


// Route::view('apps/contacts/lists', 'apps.contacts.lists')->name('contacts-lists');
// Route::view('apps/contacts/contact-details', 'apps.contacts.contact-details')->name('contact-details');
// Route::view('apps/contacts/grid', 'apps.contacts.grid')->name('contacts-grid');

// // forms
// Route::view('forms/basic-action-bar', 'forms.basic-action-bar')->name('basic-action-bar');
// Route::view('forms/multi-column-forms', 'forms.multi-column-forms')->name('multi-column-forms');
// Route::view('forms/smartWizard', 'forms.smartWizard')->name('smartWizard');
// Route::view('forms/tagInput', 'forms.tagInput')->name('tagInput');
// Route::view('forms/forms-basic', 'forms.forms-basic')->name('forms-basic');
// Route::view('forms/form-layouts', 'forms.form-layouts')->name('form-layouts');
// Route::view('forms/form-input-group', 'forms.form-input-group')->name('form-input-group');
// Route::view('forms/form-validation', 'forms.form-validation')->name('form-validation');
// Route::view('forms/form-editor', 'forms.form-editor')->name('form-editor');

// // Charts
// Route::view('charts/echarts', 'charts.echarts')->name('echarts');
// Route::view('charts/chartjs', 'charts.chartjs')->name('chartjs');
// Route::view('charts/apexLineCharts', 'charts.apexLineCharts')->name('apexLineCharts');
// Route::view('charts/apexAreaCharts', 'charts.apexAreaCharts')->name('apexAreaCharts');
// Route::view('charts/apexBarCharts', 'charts.apexBarCharts')->name('apexBarCharts');
// Route::view('charts/apexColumnCharts', 'charts.apexColumnCharts')->name('apexColumnCharts');
// Route::view('charts/apexRadialBarCharts', 'charts.apexRadialBarCharts')->name('apexRadialBarCharts');
// Route::view('charts/apexRadarCharts', 'charts.apexRadarCharts')->name('apexRadarCharts');
// Route::view('charts/apexPieDonutCharts', 'charts.apexPieDonutCharts')->name('apexPieDonutCharts');
// Route::view('charts/apexSparklineCharts', 'charts.apexSparklineCharts')->name('apexSparklineCharts');
// Route::view('charts/apexScatterCharts', 'charts.apexScatterCharts')->name('apexScatterCharts');
// Route::view('charts/apexBubbleCharts', 'charts.apexBubbleCharts')->name('apexBubbleCharts');
// Route::view('charts/apexCandleStickCharts', 'charts.apexCandleStickCharts')->name('apexCandleStickCharts');
// Route::view('charts/apexMixCharts', 'charts.apexMixCharts')->name('apexMixCharts');

// // datatables
// Route::view('datatables/basic-tables', 'datatables.basic-tables')->name('basic-tables');



// // widgets
// Route::view('widgets/card', 'widgets.card')->name('widget-card');
// Route::view('widgets/statistics', 'widgets.statistics')->name('widget-statistics');
// Route::view('widgets/list', 'widgets.list')->name('widget-list');
// Route::view('widgets/app', 'widgets.app')->name('widget-app');
// Route::view('widgets/weather-app', 'widgets.weather-app')->name('widget-weather-app');

// // others
// Route::view('others/notFound', 'others.notFound')->name('notFound');
// Route::view('others/user-profile', 'others.user-profile')->name('user-profile');
// Route::view('others/starter', 'starter')->name('starter');
// Route::view('others/faq', 'others.faq')->name('faq');
// Route::view('others/pricing-table', 'others.pricing-table')->name('pricing-table');
// Route::view('others/search-result', 'others.search-result')->name('search-result');