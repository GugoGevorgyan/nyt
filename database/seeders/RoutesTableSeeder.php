<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoutesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('routes')->delete();

        \DB::table('routes')->insert(array (
            0 =>
            array (
                'alias' => 'cars',
                'as' => 'cars',
                'middleware' => '{"0": "check_franchise_module_roles", "1": "check_franchise_has_user_modules:Module::PERSONAL_DEPARTMENT", "2": "auth:system_workers_web", "3": "guard_detect", "value": "check_franchise_modules:Module::PERSONAL_DEPARTMENT"}',
                'name' => 'get_dashboard_page',
                'namespace' => NULL,
                'prefix' => 'app/worker/',
                'route_id' => 1,
                'type' => 'get',
                'url' => 'dashboard',
            ),
            1 =>
            array (
                'alias' => 'cars_attach_driver',
                'as' => 'cars_attach_driver',
                'middleware' => '["check_franchise_modules:Module::PERSONAL_DEPARTMENT", "check_franchise_module_roles", "check_franchise_has_user_modules:Module::PERSONAL_DEPARTMENT", "auth:system_workers_web", "guard_detect"]',
                'name' => 'get_driver_candidates',
                'namespace' => NULL,
                'prefix' => 'app/worker/',
                'route_id' => 2,
                'type' => 'get',
                'url' => 'driver-candidates',
            ),
            2 =>
            array (
                'alias' => 'cars_onlain_control',
                'as' => 'cars_onlain_control',
                'middleware' => '["check_franchise_modules:Module::PERSONAL_DEPARTMENT", "check_franchise_module_roles", "check_franchise_has_user_modules:Module::PERSONAL_DEPARTMENT", "auth:system_workers_web", "guard_detect"]',
                'name' => 'get_candidates_create',
                'namespace' => NULL,
                'prefix' => 'app/worker/',
                'route_id' => 3,
                'type' => 'get',
                'url' => 'driver-candidates/CreateComponents',
            ),
            3 =>
            array (
                'alias' => 'drivers',
                'as' => 'drivers',
                'middleware' => '["check_franchise_modules:Module::PERSONAL_DEPARTMENT", "check_franchise_module_roles", "check_franchise_has_user_modules:Module::PERSONAL_DEPARTMENT", "auth:system_workers_web", "guard_detect"]',
                'name' => 'get_driver_candidates_edit',
                'namespace' => NULL,
                'prefix' => 'app/worker/',
                'route_id' => 4,
                'type' => 'get',
                'url' => 'driver_candidates_edit',
            ),
            4 =>
            array (
                'alias' => 'show_corporate_list',
                'as' => 'show_corporate_list',
                'middleware' => '["check_franchise_modules:Module::PERSONAL_DEPARTMENT", "check_franchise_module_roles", "check_franchise_has_user_modules:Module::PERSONAL_DEPARTMENT", "auth:system_workers_web", "guard_detect"]',
                'name' => 'get_parks',
                'namespace' => NULL,
                'prefix' => 'app/worker/',
                'route_id' => 5,
                'type' => 'get',
                'url' => 'parks',
            ),
            5 =>
            array (
                'alias' => 'show_agregator_list',
                'as' => 'show_agregator_list',
                'middleware' => '["check_franchise_modules:Module::PERSONAL_DEPARTMENT", "check_franchise_module_roles", "check_franchise_has_user_modules:Module::PERSONAL_DEPARTMENT", "auth:system_workers_web", "guard_detect"]',
                'name' => 'get_system_workers',
                'namespace' => NULL,
                'prefix' => 'app/worker/',
                'route_id' => 6,
                'type' => 'get',
                'url' => 'workers',
            ),
            6 =>
            array (
                'alias' => 'traffic_safety_department',
                'as' => 'traffic_safety_department',
                'middleware' => '["check_franchise_modules:Module::PERSONAL_DEPARTMENT", "check_franchise_module_roles", "check_franchise_has_user_modules:Module::PERSONAL_DEPARTMENT", "auth:system_workers_web", "guard_detect"]',
                'name' => 'traffic_safety_department',
                'namespace' => NULL,
                'prefix' => 'app/worker/',
                'route_id' => 7,
                'type' => 'get',
                'url' => 'traffic-safety',
            ),
            7 =>
            array (
                'alias' => 'park_management',
                'as' => 'park_management',
                'middleware' => '["check_franchise_modules:Module::PERSONAL_DEPARTMENT", "check_franchise_module_roles", "check_franchise_has_user_modules:Module::PERSONAL_DEPARTMENT", "auth:system_workers_web", "guard_detect"]',
                'name' => 'park_management',
                'namespace' => NULL,
                'prefix' => 'app/worker/',
                'route_id' => 8,
                'type' => 'get',
                'url' => 'park-management',
            ),
            8 =>
            array (
                'alias' => NULL,
                'as' => NULL,
                'middleware' => '["check_franchise_modules:Module::PERSONAL_DEPARTMENT", "check_franchise_module_roles", "check_franchise_has_user_modules:Module::PERSONAL_DEPARTMENT", "auth:system_workers_web", "guard_detect"]',
                'name' => 'tariff.index',
                'namespace' => NULL,
                'prefix' => 'app/worker/',
                'route_id' => 9,
                'type' => 'get',
                'url' => 'tariff',
            ),
            9 =>
            array (
                'alias' => NULL,
                'as' => NULL,
                'middleware' => '["check_franchise_modules:Module::PERSONAL_DEPARTMENT", "check_franchise_module_roles", "check_franchise_has_user_modules:Module::PERSONAL_DEPARTMENT", "auth:system_workers_web", "guard_detect"]',
                'name' => 'get_waybills',
                'namespace' => NULL,
                'prefix' => 'app/worker/',
                'route_id' => 10,
                'type' => 'get',
                'url' => 'waybills',
            ),
            10 =>
            array (
                'alias' => NULL,
                'as' => NULL,
                'middleware' => '["check_franchise_modules:Module::PERSONAL_DEPARTMENT", "check_franchise_module_roles", "check_franchise_has_user_modules:Module::PERSONAL_DEPARTMENT", "auth:system_workers_web", "guard_detect"]',
                'name' => 'park_manager_drivers',
                'namespace' => NULL,
                'prefix' => 'app/worker/',
                'route_id' => 11,
                'type' => 'get',
                'url' => 'drivers',
            ),
            11 =>
            array (
                'alias' => NULL,
                'as' => NULL,
                'middleware' => '["check_franchise_modules:Module::PERSONAL_DEPARTMENT", "check_franchise_module_roles", "check_franchise_has_user_modules:Module::PERSONAL_DEPARTMENT", "auth:system_workers_web", "guard_detect"]',
                'name' => 'show_company_index',
                'namespace' => NULL,
                'prefix' => 'app/worker/',
                'route_id' => 12,
                'type' => 'get',
                'url' => 'company',
            ),
            12 =>
            array (
                'alias' => NULL,
                'as' => NULL,
                'middleware' => '["check_franchise_modules:Module::PERSONAL_DEPARTMENT", "check_franchise_module_roles", "check_franchise_has_user_modules:Module::PERSONAL_DEPARTMENT", "auth:system_workers_web", "guard_detect"]',
                'name' => 'get_feedbacks_index',
                'namespace' => NULL,
                'prefix' => 'app/worker/',
                'route_id' => 13,
                'type' => 'get',
                'url' => 'feedbacks',
            ),
            13 =>
            array (
                'alias' => NULL,
                'as' => NULL,
                'middleware' => '["check_franchise_modules:Module::PERSONAL_DEPARTMENT", "check_franchise_module_roles", "check_franchise_has_user_modules:Module::PERSONAL_DEPARTMENT", "auth:system_workers_web", "guard_detect"]',
                'name' => 'contract_signing',
                'namespace' => NULL,
                'prefix' => 'app/worker/',
                'route_id' => 14,
                'type' => 'get',
                'url' => 'contract-signing',
            ),
            14 =>
            array (
                'alias' => NULL,
                'as' => NULL,
                'middleware' => '["check_franchise_modules:Module::PERSONAL_DEPARTMENT", "check_franchise_module_roles", "check_franchise_has_user_modules:Module::PERSONAL_DEPARTMENT", "auth:system_workers_web", "guard_detect"]',
                'name' => 'get_schedule_info',
                'namespace' => NULL,
                'prefix' => 'app/worker/',
                'route_id' => 15,
                'type' => 'get',
                'url' => 'schedule',
            ),
            15 =>
            array (
                'alias' => NULL,
                'as' => NULL,
                'middleware' => '["check_franchise_modules:Module::PERSONAL_DEPARTMENT", "check_franchise_module_roles", "check_franchise_has_user_modules:Module::PERSONAL_DEPARTMENT", "auth:system_workers_web", "guard_detect"]',
                'name' => 'driver_types',
                'namespace' => NULL,
                'prefix' => 'app/worker/',
                'route_id' => 16,
                'type' => 'get',
                'url' => 'driver-types',
            ),
            16 =>
            array (
                'alias' => NULL,
                'as' => NULL,
                'middleware' => '["check_franchise_modules:Module::PERSONAL_DEPARTMENT", "check_franchise_module_roles", "check_franchise_has_user_modules:Module::PERSONAL_DEPARTMENT", "auth:system_workers_web", "guard_detect"]',
                'name' => 'show_complaint_index',
                'namespace' => NULL,
                'prefix' => 'app/worker/',
                'route_id' => 17,
                'type' => 'get',
                'url' => 'complaint',
            ),
            17 =>
            array (
                'alias' => NULL,
                'as' => NULL,
                'middleware' => '["check_franchise_modules:Module::PERSONAL_DEPARTMENT", "check_franchise_module_roles", "check_franchise_has_user_modules:Module::PERSONAL_DEPARTMENT", "auth:system_workers_web", "guard_detect"]',
                'name' => 'show_driver_contracts',
                'namespace' => NULL,
                'prefix' => 'app/worker/',
                'route_id' => 19,
                'type' => 'get',
                'url' => 'driver-contracts',
            ),
            18 =>
            array (
                'alias' => NULL,
                'as' => NULL,
                'middleware' => '["check_franchise_modules:Module::PERSONAL_DEPARTMENT", "check_franchise_module_roles", "check_franchise_has_user_modules:Module::PERSONAL_DEPARTMENT", "auth:system_workers_web", "guard_detect"]',
                'name' => 'call_center',
                'namespace' => NULL,
                'prefix' => 'app/worker/',
                'route_id' => 20,
                'type' => 'get',
                'url' => 'call-center',
            ),
            19 =>
            array (
                'alias' => NULL,
                'as' => NULL,
                'middleware' => '["check_franchise_modules:Module::PERSONAL_DEPARTMENT", "check_franchise_module_roles", "check_franchise_has_user_modules:Module::PERSONAL_DEPARTMENT", "auth:system_workers_web", "guard_detect"]',
                'name' => 'call_center_dispatcher',
                'namespace' => NULL,
                'prefix' => 'app/worker/',
                'route_id' => 21,
                'type' => 'get',
                'url' => 'call-center-dispatcher',
            ),
            20 =>
            array (
                'alias' => NULL,
                'as' => NULL,
                'middleware' => '["check_franchise_modules:Module::PERSONAL_DEPARTMENT", "check_franchise_module_roles", "check_franchise_has_user_modules:Module::PERSONAL_DEPARTMENT", "auth:system_workers_web", "guard_detect"]',
                'name' => 'legal_entity_index',
                'namespace' => NULL,
                'prefix' => 'app/worker/',
                'route_id' => 22,
                'type' => 'get',
                'url' => 'legal-entity',
            ),
            21 =>
            array (
                'alias' => NULL,
                'as' => NULL,
                'middleware' => '["check_franchise_modules:Module::PERSONAL_DEPARTMENT", "check_franchise_module_roles", "check_franchise_has_user_modules:Module::PERSONAL_DEPARTMENT", "auth:system_workers_web", "guard_detect"]',
                'name' => 'bookkeeping_all_index',
                'namespace' => NULL,
                'prefix' => 'app/worker/',
                'route_id' => 23,
                'type' => 'get',
                'url' => 'bookkeeping',
            ),
            22 =>
            array (
                'alias' => NULL,
                'as' => NULL,
                'middleware' => '["check_franchise_modules:Module::PERSONAL_DEPARTMENT", "check_franchise_module_roles", "check_franchise_has_user_modules:Module::PERSONAL_DEPARTMENT", "auth:system_workers_web", "guard_detect"]',
                'name' => 'profile_index',
                'namespace' => NULL,
                'prefix' => 'app/worker/',
                'route_id' => 24,
                'type' => 'get',
                'url' => 'profile',
            ),
            23 =>
            array (
                'alias' => NULL,
                'as' => NULL,
                'middleware' => '[]',
                'name' => 'clients_index',
                'namespace' => NULL,
                'prefix' => '',
                'route_id' => 25,
                'type' => 'get',
                'url' => 'clients',
            ),
            24 =>
            array (
                'alias' => NULL,
                'as' => NULL,
                'middleware' => '[]',
                'name' => 'penalties_index',
                'namespace' => NULL,
                'prefix' => 'app/worker/',
                'route_id' => 26,
                'type' => 'get',
                'url' => 'penalties',
            ),
            25 =>
                array (
                    'alias' => NULL,
                    'as' => NULL,
                    'middleware' => '[]',
                    'name' => 'bookkeeping_companies',
                    'namespace' => NULL,
                    'prefix' => 'app/worker/',
                    'route_id' => 27,
                    'type' => 'get',
                    'url' => 'bookkeeping/companies',
                ),
            26 =>
                array (
                    'alias' => NULL,
                    'as' => NULL,
                    'middleware' => '[]',
                    'name' => 'bookkeeping_drivers',
                    'namespace' => NULL,
                    'prefix' => 'app/worker/',
                    'route_id' => 28,
                    'type' => 'get',
                    'url' => 'bookkeeping/drivers',
                ),
        ));


    }
}
