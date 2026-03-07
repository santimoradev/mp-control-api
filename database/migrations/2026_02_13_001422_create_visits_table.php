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
        Schema::create('routes', function (Blueprint $table) {
          $table->id();
          $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete();
          $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
          $table->string('title');
          $table->timestamp('start_date');
          $table->timestamp('end_date');
          $table->integer('status'); // 1 Pendiente 2-En progreso 3-Completada 4-No finalizada 5-Cancelada
          $table->timestamps();
          $table->softDeletes();
        });
        Schema::create('visits', function (Blueprint $table) {
          $table->id();
          $table->foreignId('route_id')->constrained('routes')->cascadeOnDelete();
          $table->foreignId('location_id')->constrained('locations')->cascadeOnDelete();
          $table->foreignId('assigned_to')->constrained('users')->cascadeOnDelete();
          $table->timestamp('scheduled_date');
          $table->timestamp('check_in')->nullable();
          $table->timestamp('check_out')->nullable();
          $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
          $table->decimal('lat', 10, 7)->nullable();
          $table->decimal('lng', 10, 7)->nullable();
          $table->integer('status')->default(1); // 1-Pending 2-In progress 3-Finalizada 4-Perdida 0-Cancelada
          $table->timestamps();
          $table->softDeletes();
        });
        Schema::create('comments', function (Blueprint $table) {
          $table->id();
          $table->foreignId('visit_id')->constrained('visits')->cascadeOnDelete();
          $table->string('title');
          $table->text('message');
          $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
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
      Schema::dropIfExists('comments');
      Schema::dropIfExists('visits');
      Schema::dropIfExists('routes');
    }
};
