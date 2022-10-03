<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Src\Models\Franchise\Franchise;
use Src\Models\Franchise\FranchiseModule;
use Src\Models\Franchise\Module;
use Src\Models\Role\FranchiseRole;

/**
 * Class FranchiseeModuleTableSeeder
 */
class FranchiseeModuleTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run(): void
    {
        $modules = Module::with('roles')->get();
        $franchises = Franchise::get();

        foreach ($franchises as $franchise) {
            foreach ($modules as $module) {
                $franchise_module = FranchiseModule::create([
                    'franchise_id' => $franchise->franchise_id,
                    'module_id' => $module->module_id
                ]);

                foreach ($module->roles as $role) {
                    FranchiseRole::create([
                        'franchise_module_id' => $franchise_module->franchise_module_id,
                        'franchise_id' => $franchise->franchise_id,
                        'role_id' => $role->role_id
                    ]);
                }
            }
        }
    }
}
