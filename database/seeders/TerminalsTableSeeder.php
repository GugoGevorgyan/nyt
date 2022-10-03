<?php

declare(strict_types=1);

namespace Database\Seeders;

use DB;
use Hash;
use Illuminate\Database\Seeder;
use Src\Core\Enums\ConsTerminalPwd;
use Src\Models\Park;
use Src\Models\Terminal\Terminal;

/**
 * Class TerminalsTableSeeder
 */
class TerminalsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('terminals')->delete();

        $parks = Park::get();

        foreach ($parks as $park) {
            Terminal::create([
                'created_at' => null,
                'name' => 'Terminal'.$park->park_id,
                'password' => Hash::make(ConsTerminalPwd::FIRST()->getValue()),
                'park_id' => $park->park_id,
                'updated_at' => null,
            ]);
        }
    }
}
