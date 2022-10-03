<?php

namespace Database\Seeders;

use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Src\Models\Complaint\Complaint;
use Src\Models\Complaint\ComplaintComment;

class ComplaintCommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $complaints = Complaint::get();

        foreach ($complaints as $complaint) {
            $ids = [$complaint->writer_id, $complaint->recipient_id, 1];

            for ($i = 0; $i < 12; $i++) {
                ComplaintComment::create([
                    'complaint_id' => $complaint->complaint_id,
                    'worker_id' => $ids[rand(0, 1)],
                    'text' => $faker->text
                ]);
            }
        }
    }
}
