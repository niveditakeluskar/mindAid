<?php

namespace RCare\Org\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class OrgServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
       // $this->app->bind(
       //     'Illuminate\Contracts\Auth\Registrar',
          //  'App\Services\Registrar'
     //   );
        $this->app->register('RCare\Org\OrgPackages\Roles\src\Providers\UserRolesServiceProvider');
        $this->app->register('RCare\Org\OrgPackages\Users\src\Providers\UserServiceProvider');
        $this->app->register('RCare\Org\OrgPackages\Modules\src\Providers\ModuleServiceProvider');
        $this->app->register('RCare\Org\OrgPackages\Menus\src\Providers\MenuServiceProvider');
        $this->app->register('RCare\Org\OrgPackages\Practices\src\Providers\PracticeServiceProvider');
        $this->app->register('RCare\Org\OrgPackages\Physicians\src\Providers\PhysicianServiceProvider');
        $this->app->register('RCare\Org\OrgPackages\Stages\src\Providers\StageServiceProvider');
        $this->app->register('RCare\Org\OrgPackages\StageCodes\src\Providers\StageCodesServiceProvider');
        $this->app->register('RCare\Org\OrgPackages\QCTemplates\src\Providers\QCTemplatesServiceProvider');
        $this->app->register('RCare\Org\OrgPackages\Diagnosis\src\Providers\DiagnosisServiceProvider');
        $this->app->register('RCare\Org\OrgPackages\Devices\src\Providers\DevicesServiceProvider');
        $this->app->register('RCare\Org\OrgPackages\Labs\src\Providers\LabsServiceProvider');
        $this->app->register('RCare\Org\OrgPackages\Activity\src\Providers\ActivityServiceProvider'); 
        $this->app->register('RCare\Org\OrgPackages\CarePlanTemplate\src\Providers\CarePlanTemplateServiceProvider');
        $this->app->register('RCare\Org\OrgPackages\Medication\src\Providers\MedicationServiceProvider');
        $this->app->register('RCare\Org\OrgPackages\Providers\src\Providers\ProviderServiceProvider');
        $this->app->register('RCare\Org\OrgPackages\FollowupTask\src\Providers\FollowupTaskServiceProvider');
        $this->app->register('RCare\Org\OrgPackages\Offices\src\Providers\OfficeServiceProvider');
        $this->app->register('RCare\Org\OrgPackages\Responsibility\src\Providers\ResponsibilityServiceProvider');
        $this->app->register('RCare\Org\OrgPackages\Scheduler\src\Providers\SchedulerServiceProvider');
        $this->app->register('RCare\Org\OrgPackages\DeactivationReasons\src\Providers\DeactivationReasonsServiceProvider'); 
        $this->app->register('RCare\Org\OrgPackages\Threshold\src\Providers\ThresholdServiceProvider');
        $this->app->register('RCare\Org\OrgPackages\RPMBillingConfiguration\src\Providers\RPMBillingConfigurationServiceProvider');
        $this->app->register('RCare\Org\OrgPackages\Protocol\src\Providers\ProtocolServiceProvider');
        $this->app->register('RCare\Org\OrgPackages\Partner\src\Providers\PartnerServiceProvider');
        $this->app->register('RCare\Org\OrgPackages\RPMAlert\src\Providers\RPMAlertServiceProvider');
        $this->app->register('RCare\Org\OrgPackages\PartnerApiDetails\src\Providers\PartnerApiDetailsServiceProvider');
        $this->app->register('RCare\Org\OrgPackages\DomainFeatures\src\Providers\DomainFeaturesServiceProvider');
        $this->app->register('RCare\Org\OrgPackages\ReportsMaster\src\Providers\ReportsMasterServiceProvider');
        $this->app->register('RCare\Org\OrgPackages\Calender\src\Providers\CalenderServiceProvider'); 
		$this->app->register('RCare\Org\OrgPackages\Holiday\src\Providers\HolidayServiceProvider'); 
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // include __DIR__.'/../Http/routes.php';
        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');

        $this->publishes([
            __DIR__ . '/../../publishable/assets' => public_path('themes/default/assets'),
        ], 'public');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'Org');
        $this->loadViewsFrom(__DIR__ . '/../../../Theme/src/Resources/views', 'Theme');
    }
}
