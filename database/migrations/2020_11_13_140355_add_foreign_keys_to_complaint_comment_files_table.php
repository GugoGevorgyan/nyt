<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToComplaintCommentFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('complaint_comment_files', function (Blueprint $table) {
            $table->foreign('complaint_comment_id',
                'complaint_comment_files_complaint_comment_id')->references('complaint_comment_id')->on('complaint_comments')
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
        Schema::table('complaint_comment_files', function (Blueprint $table) {
            $table->dropForeign('complaint_comment_files_complaint_comment_id');
        });
    }
}
