<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToFranchiseSubPhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('franchise_sub_phones', function (Blueprint $table) {
            $table
                ->foreign('franchise_phone_id', 'franchise_sub_phones_foreign_franchise_phone_id')
                ->references('franchise_phone_id')
                ->on('franchise_phones')
                ->onUpdate('CASCADE')
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
        Schema::table('franchise_sub_phones', function (Blueprint $table) {
            $table->dropForeign('franchise_sub_phones_foreign_franchise_phone_id');
        });
    }
}
