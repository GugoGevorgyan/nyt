<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateLegalEntityTypesTable
 */
class CreateLegalEntityTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('legal_entity_types', function (Blueprint $table) {
            $table->increments('entity_type_id')->index('index_entity_type_id');
            $table->string('abbreviation');
            $table->string('name');
            $table->string('value')->unique();
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
        Schema::dropIfExists('legal_entity_types');
    }
}
