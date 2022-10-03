<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Src\Core\Enums\ConstAreaType;

/**
 * Class CreateAreasTable
 */
class CreateAreasTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'areas',
            static function (Blueprint $table) {
                $table->increments('area_id')->index('index_area_id');
                $table->json('region')->nullable();
                $table->unsignedTinyInteger('type')->default(ConstAreaType::FRANCHISE()->getValue());
                $table->unsignedTinyInteger('object_type')->default(ConstAreaType::FRANCHISE()->getValue());
                $table->string('name')->unique();
                $table->string('title')->nullable();
                $table->text('description')->nullable();
                $table->json('area')->nullable();
                $table->polygon('geo_area')->nullable();
                $table->point('cord')->nullable();
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
        Schema::drop('attributes');
    }

}
