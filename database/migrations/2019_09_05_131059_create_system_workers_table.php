<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateSystemWorkersTable
 */
class CreateSystemWorkersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'system_workers',
            function (Blueprint $table) {
                $table->increments('system_worker_id')->index('index_system_worker_id');
                $table->integer('franchise_id')->unsigned()->index('system_workers_franchise_id');
                $table->boolean('is_admin')->default(0);
                $table->integer('graphic_id')->unsigned()->nullable()->index('system_workers_graphic_id')->comment('график работы');
                $table->string('name', 150);
                $table->string('surname', 150)->nullable();
                $table->string('patronymic', 150)->nullable();
                $table->string('nickname', 150)->unique();
                $table->string('email', 120)->nullable()->unique();
                $table->string('remember_token', 150)->nullable();
                $table->string('password');
                $table->string('phone')->nullable();
                $table->string('description', 300)->nullable();
                $table->string('photo', 128)->nullable();
                $table->smallInteger('rating')->nullable();
                $table->boolean('logged')->default(false);
                $table->boolean('in_session')->default(false);
                $table->softDeletes();
                $table->timestamps(6);
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
        Schema::drop('system_workers');
    }

}
