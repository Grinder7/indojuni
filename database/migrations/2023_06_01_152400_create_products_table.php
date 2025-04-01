<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('stock')->default(0);
            $table->integer('price');
            $table->text('description')->nullable();
            $table->string('img');
        });
        DB::statement("ALTER TABLE products ADD COLUMN search_vector tsvector GENERATED ALWAYS AS (
            setweight(to_tsvector('indonesian', name), 'A') ||
            setweight(to_tsvector('english', name), 'B')
        ) STORED");
        DB::statement(" CREATE INDEX products_search_vector_idx ON products USING GIN (search_vector)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['products_name_tsvector_idx']); // Drop old index if exists
        });
        Schema::dropIfExists('products');
    }
};
