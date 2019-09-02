<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSearchIndicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('search_indices', function (Blueprint $table) {
            $table->bigIncrements('id');;
            $table->morphs('searchable');
            $table->string('link');
            $table->jsonb('meta')->nullable();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE search_indices ADD COLUMN vector TSVECTOR');
        DB::statement('CREATE INDEX vector_index ON search_indices USING GIN (vector)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('search_indices');
    }
}
