<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->increments('complaint_id')->index('index_complaint_id');
            $table->unsignedInteger('franchise_id')->index('complaints_franchise_id');
            $table->unsignedInteger('order_id')->nullable()->index('complaints_order_id');
            $table->unsignedInteger('writer_id')->index('complaints_writer_id')->comment('Кто написал жалобу');
            $table->unsignedInteger('recipient_id')->index('complaints_recipient_id')->comment('На кого написали жалобу');
            $table->unsignedInteger('status_id')->index('complaints_status_id')->comment('На каком этапе расмотрения жалоба');
            $table->text('subject');
            $table->text('complaint')->comment('Жалоба');
            $table->timestamps(6);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('complaints');
    }
}
