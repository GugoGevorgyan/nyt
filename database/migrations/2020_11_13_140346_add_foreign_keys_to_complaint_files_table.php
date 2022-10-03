<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToComplaintFilesTable
 */
class AddForeignKeysToComplaintFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('complaint_files', function (Blueprint $table) {
            $table->foreign('complaint_id',
                'complaint_files_complaint_id')->references('complaint_id')->on('complaints')
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
        Schema::table('complaint_files', function (Blueprint $table) {
            $table->dropForeign('complaint_files_complaint_id');
        });
    }
}
