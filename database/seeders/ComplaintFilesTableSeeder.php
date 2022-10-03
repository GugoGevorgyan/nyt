<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Src\Models\Complaint\Complaint;

class ComplaintFilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $complaints = Complaint::get();

        foreach ($complaints as $complaint) {
            if (rand(0, 1)) {
                $count = rand(2, 10);
                for ($i = 0; $i < $count; $i++) {
                    $complaint->files()->create(['file' => '/storage/seeders/complaints/test'.rand(1, 7).'.jpg']);
                }
            }
        }
    }
}
