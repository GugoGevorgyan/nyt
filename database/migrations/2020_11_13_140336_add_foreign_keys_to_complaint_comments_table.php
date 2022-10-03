<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToComplaintCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('complaint_comments', function (Blueprint $table) {
            $table->foreign('complaint_id',
                'complaint_comments_complaint_id')->references('complaint_id')->on('complaints')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');
            $table->foreign('worker_id',
                'complaint_comments_worker_id')->references('system_worker_id')->on('system_workers')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('complaint_comments', function (Blueprint $table) {
            $table->dropForeign('complaint_comments_complaint_id');
            $table->dropForeign('complaint_comments_worker_id');
        });
    }
}
