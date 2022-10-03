<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 *
 */
class CreateMenuRoleTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('menu_role', function (Blueprint $table) {
            $table->increments('menu_role_id')->index('index_menu_role_id');
            $table->unsignedInteger('menu_id')->index('menu_role_menu_id');
            $table->unsignedInteger('role_id')->index('menu_role_role_id');
            $table->unsignedInteger('permission_id')->nullable()->index('menu_permission_id');
            $table->timestamp('created_at')->nullable()->default(\Illuminate\Support\Facades\DB::raw('CURRENT_TIMESTAMP'));
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('menu_role');
    }

}
