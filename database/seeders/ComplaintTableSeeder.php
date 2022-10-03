<?php

declare(strict_types=1);

namespace Database\Seeders;

use DB;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Src\Models\Complaint\Complaint;
use Src\Models\Order\Order;
use Src\Models\SystemUsers\SystemWorker;

/**
 * Class ComplaintTableSeeder
 */
class ComplaintTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param  Faker  $faker
     * @return void
     * @throws Exception
     */
    public function run(Faker $faker): void
    {
        $workers = SystemWorker::where('franchise_id', '=', 1)->get();
        $orders = Order::whereHas('franchise', function ($q) {
            $q->where('franchise_id', '=', 1);
        })->get();
        DB::table('complaints')->delete();

        foreach ($workers as $worker) {
            for ($x = 0; $x < 10; $x++) {
                $recipient_id = $this->getRecipientId($workers, $worker->system_worker_id);
                Complaint::create([
                    'writer_id' => $worker->system_worker_id,
                    'franchise_id' => 1,
                    'recipient_id' => $recipient_id,
                    'status_id' => random_int(1, 3),
                    'order_id' => $orders[random_int(0, count($orders) - 1)]->order_id,
                    'subject' => $faker->text,
                    'complaint' => $faker->text
                ]);
            }
        }
    }

    /**
     * @throws Exception
     */
    protected function getRecipientId($workers, $worker_id)
    {
        $result = $workers[random_int(0, count($workers) - 1)]->system_worker_id;

        return $result === $worker_id ? $this->getRecipientId($workers, $worker_id) : $result;
    }
}
