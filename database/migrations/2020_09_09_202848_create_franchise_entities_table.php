<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateFranchiseEntitiesTable
 */
class CreateFranchiseEntitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'franchise_entities',
            function (Blueprint $table) {
                $table->increments('franchise_entity_id')->index('index_franchise_entity_id');
                $table->unsignedInteger('legal_entity_id')->index('franchise_entities_entity_id');
                $table->unsignedInteger('franchise_id')->index('franchise_entities_franchise_id');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('franchise_entities');
    }
}
