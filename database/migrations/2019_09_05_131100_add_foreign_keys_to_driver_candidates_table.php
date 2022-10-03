<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToDriverCandidatesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('driver_candidates', function (Blueprint $table) {
            $table
                ->foreign('driver_info_id', 'candidate_driver_info_id')
                ->references('driver_info_id')
                ->on('drivers_info')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');

            $table
                ->foreign('tutor_id', 'candidate_tutor_id')
                ->references('system_worker_id')
                ->on('system_workers')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');

        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('driver_candidates', function (Blueprint $table) {
            $table->dropForeign('candidate_driver_info_id');
            $table->dropForeign('candidate_tutor_id');
        });
    }

}
