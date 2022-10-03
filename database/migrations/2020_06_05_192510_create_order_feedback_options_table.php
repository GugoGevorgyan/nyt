<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateOrderFeedbackOptionsTable
 */
class CreateOrderFeedbackOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'order_feedback_options',
            static function (Blueprint $table) {
                $table->increments('order_feedback_option_id')->index('order_feedback_options_index_id');
                $table->unsignedTinyInteger('option');
                $table->string('name', 100);
                $table->boolean('completed')->default(false);
                $table->boolean('canceled')->default(false);
                $table->string('owner_type', 120);
                $table->set('assessment', [0, 1, 2, 3, 4, 5])->default(0);
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_feedback_options');
    }
}
