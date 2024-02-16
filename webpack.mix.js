let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
    /* CSS */

     

    .js('resources/laravel/js/iapp.js', 'public/assets/js/laravel/iapp.js')
    .js('resources/gull/assets/js/vendor/jquery-migrate-3.0.0.min.js', 'public/assets/js/vendor/jquery-migrate-3.0.0.min.js')
   /* .js('resources/laravel/js/select2.min.js', 'public/assets/js/laravel/select2.min.js')*/
   
    

    //.js('resources/gull/assets/js/libs/bootstrap-1.13.14-select.min.js', 'public/assets/js/external-js/bootstrap-1.13.14-select.min.js') 
    
   /* .js('resources/laravel/js/external-js/export_child_table.js', 'public/assets/js/external-js/export_child_table.js')*/
   // .js('resources/laravel/js/external-js/ckeditor.js', 'public/assets/js/external-js/ckeditor.js')
    .js('resources/laravel/js/commonHighchart.js', 'public/assets/js/laravel/commonHighchart.js')
    //.js('resources/laravel/js/external-js/export-child-table/buttons.html5.js', 'public/assets/js/external-js/export-child-table/buttons.html5.js')


    .js('resources/laravel/js/ccmMonthlyMonitoring.js', 'public/assets/js/laravel/ccmMonthlyMonitoring.js')
    .js('resources/laravel/js/ccmcpdcommonJS.js', 'public/assets/js/laravel/ccmcpdcommonJS.js')
    .js('resources/laravel/js/carePlanDevelopment.js', 'public/assets/js/laravel/carePlanDevelopment.js')
    .js('resources/laravel/js/rpmlist.js', 'public/assets/js/laravel/rpmlist.js')
    .js('resources/laravel/js/activealert.js', 'public/assets/js/laravel/activealert.js')
    .js('resources/laravel/js/rpmMonthlyServices.js', 'public/assets/js/laravel/rpmMonthlyServices.js')
    .js('resources/laravel/js/alerthistory.js', 'public/assets/js/laravel/alerthistory.js')
    .js('resources/laravel/js/apiexception.js', 'public/assets/js/laravel/apiexception.js')
    .js('resources/laravel/js/rpmPatientDeviceReading.js', 'public/assets/js/laravel/rpmPatientDeviceReading.js')
    .js('resources/laravel/js/rpmworklist.js', 'public/assets/js/laravel/rpmworklist.js')
    .js('resources/laravel/js/deviceOrder.js', 'public/assets/js/laravel/deviceOrder.js')
    .js('resources/laravel/js/rpmReviewDataLink.js', 'public/assets/js/laravel/rpmReviewDataLink.js')
    .js('resources/laravel/js/patientRegistration.js', 'public/assets/js/laravel/patientRegistration.js')
    .js('resources/laravel/js/editPatientRegistration.js', 'public/assets/js/laravel/editPatientRegistration.js')
    .js('resources/laravel/js/worklist.js', 'public/assets/js/laravel/worklist.js')
    .js('resources/laravel/js/patientEnrollment.js', 'public/assets/js/laravel/patientEnrollment.js')
    .js('resources/laravel/js/taskManage.js', 'public/assets/js/laravel/taskManage.js')
    .js('resources/laravel/js/stageCode.js', 'public//assets/js/laravel/stageCode.js')

    .sass('resources/gull/assets/styles/sass/themes/lite-purple.scss', 'public/assets/styles/css/themes/lite-purple.min.css')
    .sass('resources/gull/assets/styles/sass/themes/lite-blue.scss', 'public/assets/styles/css/themes/lite-blue.min.css')
    .sass('resources/gull/assets/styles/sass/themes/dark-purple.scss', 'public/assets/styles/css/themes/dark-purple.min.css')
    .sass('resources/laravel/sass/app.scss', 'public/assets/sass/css')
    .css('resources/gull/assets/styles/vendor/googlefont/googlefontNunito.css', 'public/assets/sass/css/googlefontNunito.css')
    // added by pri on 29th oct21 
    // .css('resources/gull/assets/styles/sass/themes/jquery-ui.css', 'public/assets/styles/css/themes/jquery-ui.css') 
    
    //.css('resources/gull/assets/styles/vendor/googlefont/fontsgoogleapis.css', 'public/assets/sass/css/fontsgoogleapis.css')
    //.css('resources/gull/assets/styles/vendor/select2-min.css', 'public/assets/styles/vendor/multiselect/select2-min.css');

   //mix.js(['resources/gull/assets/js/libs/exporting.js'], 'public/assets/js/exporting.js');


/* JS */

/* Laravel JS */


mix.combine([
    'resources/gull/assets/js/vendor/jquery-3.3.1.min.js',
    'resources/gull/assets/js/vendor/bootstrap.bundle.min.js',
    'resources/gull/assets/js/vendor/perfect-scrollbar.min.js',
], 'public/assets/js/common-bundle-script.js');

mix.combine([
    'resources/gull/assets/js/libs/moment.min.js',
    'resources/gull/assets/js/libs/moment-timezone-with-data.js',
], 'public/assets/js/moment.min.js');

mix.js([

    'resources/gull/assets/js/script.js',
], 'public/assets/js/script.js');

mix.js([

    'resources/gull/assets/js/customizer.script.js',
], 'public/assets/js/customizer.script.js');


mix.js([
    'resources/gull/assets/js/libs/jquery.validate.min.js',
], 'public/assets/js/jquery.validate.min.js');



//if (mix.inProduction()) {
mix.version();
//}