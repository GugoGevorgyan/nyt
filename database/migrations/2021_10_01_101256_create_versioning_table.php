<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 *
 */
class CreateVersioningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('versioning', function (Blueprint $table) {
            $table->increments('version_id')->index('versioning_id');
            $table->string('version', 30)->index('versioning_version');
            $table->unsignedTinyInteger('app')->index('versioning_app');
            $table->unsignedTinyInteger('device')->index('versioning_device');
            $table->unsignedTinyInteger('state')->index('versioning_state');
            $table->char('auth_key', 32)->index('versioning_auth_key');
            $table->timestamp('updated_at')->default(\Illuminate\Support\Facades\DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
//        Schema::dropIfExists('versioning');
    }
}
