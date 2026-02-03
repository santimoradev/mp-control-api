<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
      Schema::create('provinces', function (Blueprint $table) {
        $table->id();
        $table->string('name');
      });

      Schema::create('cities', function (Blueprint $table) {
        $table->id();
        $table->foreignId('province_id')
              ->constrained()
              ->cascadeOnDelete();
        $table->string('name');

        $table->index('province_id');
      });

      Schema::create('locations', function (Blueprint $table) {
        $table->id();
        $table->string('name');

        $table->foreignId('province_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->foreignId('city_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->foreignId('media_id')
              ->nullable()
              ->constrained()
              ->nullOnDelete();

        $table->integer('zoom');
        $table->decimal('latitude', 10, 7)->nullable();
        $table->decimal('longitude', 10, 7)->nullable();
        $table->string('address')->nullable();
        $table->text('description');

        $table->timestamps();
        $table->softDeletes();

        $table->index(['province_id', 'city_id']);
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('locations');
      Schema::dropIfExists('cities');
      Schema::dropIfExists('provinces');
    }
};
