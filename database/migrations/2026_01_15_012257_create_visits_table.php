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
        Schema::create('visits', function (Blueprint $table) {
          $table->id();

          $table->foreignId('business_id')->constrained()->cascadeOnDelete();
          $table->foreignId('user_id')->constrained()->cascadeOnDelete();
          $table->foreignId('location_id')->constrained()->cascadeOnDelete();

          $table->timestamp('started_at')->nullable();
          $table->foreignId('started_media_id')->nullable()->constrained('media')->nullOnDelete();
          $table->decimal('started_lat', 10, 7)->nullable();
          $table->decimal('started_lng', 10, 7)->nullable();

          $table->timestamp('finished_at')->nullable();
          $table->foreignId('finished_media_id')->nullable()->constrained('media')->nullOnDelete();
          $table->decimal('finished_lat', 10, 7)->nullable();
          $table->decimal('finished_lng', 10, 7)->nullable();

          $table->text('observations')->nullable();

          $table->timestamps();
          $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visits');
    }
};
