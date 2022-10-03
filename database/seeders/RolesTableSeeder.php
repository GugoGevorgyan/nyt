<?php

declare(strict_types=1);

namespace Database\Seeders;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Seeder;
use Src\Models\Role\Role;

/**
 * Class RolesTableSeeder
 */
class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            0 =>
                [
                    'module_id' => 2,
                    'name' => Role::PARK_MANAGER_WEB,
                    'text' => 'начальник парка (WEB)',
                    'guard_name' => 'system_workers_web',
                    'alias' => 'system_workers_web',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            1 =>
                [
                    'module_id' => 10,
                    'name' => Role::PARK_MANAGER_API,
                    'text' => 'начальник парка (API)',
                    'guard_name' => 'system_workers_api',
                    'alias' => 'system_workers_api',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            2 =>
                [
                    'module_id' => 1,
                    'name' => Role::DISPATCHER_WEB,
                    'text' => 'диспетчер (WEB)',
                    'guard_name' => 'system_workers_web',
                    'alias' => 'system_workers_web',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            3 =>
                [
                    'module_id' => 1,
                    'name' => Role::DISPATCHER_API,
                    'text' => 'диспетчер (API)',
                    'guard_name' => 'system_workers_api',
                    'alias' => 'system_workers_api',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            4 =>
                [
                    'module_id' => 1,
                    'name' => Role::OPERATOR_WEB,
                    'text' => 'оператор (WEB)',
                    'guard_name' => 'system_workers_web',
                    'alias' => 'system_workers_web',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            5 =>
                [
                    'module_id' => 1,
                    'name' => Role::OPERATOR_API,
                    'text' => 'оператор (API)',
                    'guard_name' => 'system_workers_api',
                    'alias' => 'system_workers_api',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            6 =>
                [
                    'module_id' => 2,
                    'name' => Role::MECHANIC_WEB,
                    'text' => 'механик (WEB)',
                    'guard_name' => 'system_workers_web',
                    'alias' => 'system_workers_web',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            7 =>
                [
                    'module_id' => 5,
                    'name' => Role::MECHANIC_API,
                    'text' => 'механик (API)',
                    'guard_name' => 'system_workers_api',
                    'alias' => 'system_workers_api',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            8 =>
                [
                    'module_id' => 9,
                    'name' => Role::TUTOR_WEB,
                    'text' => 'наставник (WEB)',
                    'guard_name' => 'system_workers_web',
                    'alias' => 'system_workers_web',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            9 =>
                [
                    'module_id' => 9,
                    'name' => Role::TUTOR_API,
                    'text' => 'наставник (API)',
                    'guard_name' => 'system_workers_api',
                    'alias' => 'system_workers_api',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            10 =>
                [
                    'module_id' => 9,
                    'text' => 'глава отдела кадров (WEB)',
                    'name' => Role::HEAD_PERSONAL_DEPARTMENT_WEB,
                    'guard_name' => 'system_workers_web',
                    'alias' => 'system_workers_api',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            11 =>
                [
                    'module_id' => 9,
                    'text' => 'глава отдела кадров (API)',
                    'name' => Role::HEAD_PERSONAL_DEPARTMENT_API,
                    'guard_name' => 'system_workers_api',
                    'alias' => 'system_workers_api',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            12 =>
                [
                    'module_id' => 9,
                    'text' => 'работник отдела кадров (WEB)',
                    'name' => Role::WORKER_PERSONAL_DEPARTMENT_WEB,
                    'guard_name' => 'system_workers_web',
                    'alias' => 'system_workers_api',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            13 =>
                [
                    'module_id' => 9,
                    'text' => 'работник отдела кадров (API)',
                    'name' => Role::WORKER_PERSONAL_DEPARTMENT_API,
                    'guard_name' => 'system_workers_api',
                    'alias' => 'system_workers_api',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],

            14 =>
                [
                    'module_id' => 6,
                    'text' => 'работник отдела безопасности движения (WEB)',
                    'name' => Role::TRAFFIC_SAFETY_WEB,
                    'guard_name' => 'system_workers_web',
                    'alias' => 'system_workers_api',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            15 =>
                [
                    'module_id' => 6,
                    'text' => 'работник отдела безопасности движения (API)',
                    'name' => Role::TRAFFIC_SAFETY_API,
                    'guard_name' => 'system_workers_api',
                    'alias' => 'system_workers_api',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],

            16 =>
                [
                    'module_id' => 1,
                    'text' => 'глава колл-центра (WEB)',
                    'name' => Role::HEAD_CALL_CENTER_WEB,
                    'guard_name' => 'system_workers_web',
                    'alias' => 'system_workers_api',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            17 =>
                [
                    'module_id' => 1,
                    'text' => 'глава колл-центра (API)',
                    'name' => Role::HEAD_CALL_CENTER_API,
                    'guard_name' => 'system_workers_api',
                    'alias' => 'system_workers_api',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],

            18 =>
                [
                    'module_id' => 7,
                    'text' => 'бухгалтер (WEB)',
                    'name' => Role::ACCOUNTANT_WEB,
                    'guard_name' => 'system_workers_web',
                    'alias' => 'system_workers_api',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            19 =>
                [
                    'module_id' => 7,
                    'text' => 'бухгалтер (API)',
                    'name' => Role::ACCOUNTANT_API,
                    'guard_name' => 'system_workers_api',
                    'alias' => 'system_workers_api',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],

            20 =>
                [
                    'module_id' => 8,
                    'text' => 'работник отдела продаж (WEB)',
                    'name' => Role::SALES_DEPARTMENT_WEB,
                    'guard_name' => 'system_workers_web',
                    'alias' => 'system_workers_api',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            21 =>
                [
                    'module_id' => 8,
                    'text' => 'работник отдела продаж (API)',
                    'name' => Role::SALES_DEPARTMENT_API,
                    'guard_name' => 'system_workers_api',
                    'alias' => 'system_workers_api',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            22 =>
                [
                    'module_id' => 2,
                    'text' => 'администратор франшизы (WEB)',
                    'name' => Role::ADMIN_FRANCHISE_WEB,
                    'guard_name' => 'system_workers_web',
                    'alias' => 'system_workers_web',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            23 =>
                [
                    'module_id' => 10,
                    'text' => 'администратор франшизы (API)',
                    'name' => Role::ADMIN_FRANCHISE_API,
                    'guard_name' => 'system_workers_api',
                    'alias' => 'system_workers_api',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],


//            24 =>
//                [
//                    'module_id'  => 1,
//                    'text'       => 'диспетчер колл-центра (WEB)',
//                    'name'       => Role::CALL_CENTER_DISPATCHER_WEB,
//                    'guard_name' => 'call_center_dispatcher_web',
//                    'alias'      => 'call_center_dispatcher_web',
//                    'created_at' => Carbon::now(),
//                    'updated_at' => Carbon::now(),
//                ],
//            25 =>
//                [
//                    'module_id'  => 1,
//                    'text'       => 'диспетчер колл-центра (API)',
//                    'name'       => Role::CALL_CENTER_DISPATCHER_API,
//                    'guard_name' => 'call_center_dispatcher_api',
//                    'alias'      => 'call_center_dispatcher_api',
//                    'created_at' => Carbon::now(),
//                    'updated_at' => Carbon::now(),
//                ],
//            26 =>
//                [
//                    'module_id'  => 1,
//                    'text'       => 'оператор колл-центра (WEB)',
//                    'name'       => Role::CALL_CENTER_OPERATOR_WEB,
//                    'guard_name' => 'call_center_operator_web',
//                    'alias'      => 'call_center_operator_web',
//                    'created_at' => Carbon::now(),
//                    'updated_at' => Carbon::now(),
//                ],
//            27 =>
//                [
//                    'module_id'  => 1,
//                    'text'       => 'оператор колл-центра (API)',
//                    'name'       => Role::CALL_CENTER_OPERATOR_API,
//                    'guard_name' => 'call_center_operator_api',
//                    'alias'      => 'call_center_operator_api',
//                    'created_at' => Carbon::now(),
//                    'updated_at' => Carbon::now(),
//                ],
        ]);
    }
}
