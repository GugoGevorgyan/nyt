<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintCommentFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaint_comment_files', function (Blueprint $table) {
            $table->increments('complaint_comment_file_id')->index('index_complaint_comment_file_id');
            $table->unsignedInteger('complaint_comment_id')->index('complaint_comment_file_complaint_id');
            $table->string('file');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('complaint_comment_files');
    }
}
