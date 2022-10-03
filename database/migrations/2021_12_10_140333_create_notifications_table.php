<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('notifications', static function (Blueprint $table) {
            $table->increments('notification_id')->index('notification_id');
            $table->string('group_number', 10)->index('group_number');

            $table->unsignedInteger('annunciator_id')->index('annunciator_id');
            $table->unsignedInteger('annunciator_type')->index('annunciator_type');
            $table->unsignedInteger('notifier_id')->index('notifier_id');
            $table->unsignedInteger('notifier_type')->index('notifier_type');

            $table->string('title', 100)->default('');
            $table->string('body', 1000)->default('');
            $table->json('payload')->nullable();
            $table->string('image', 250)->default('');
            $table->boolean('viewed')->default(false);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
}
