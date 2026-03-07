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
        Schema::create('exhibitions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('source_id')->default(1); // 1 Visit 2 CMS 3 Api External
            $table->foreignId('visit_id')->nullable()->constrained('visits')->cascadeOnDelete();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete();
            $table->foreignId('location_id')->constrained('locations')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('before_media_id')->nullable()->constrained('media')->cascadeOnDelete();
            $table->foreignId('after_media_id')->nullable()->constrained('media')->cascadeOnDelete();
            $table->text('before_description')->nullable();
            $table->text('after_description')->nullable();
            $table->timestamp('observed_at');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['business_id', 'observed_at']);
            $table->index(['location_id', 'observed_at']);
        });
        Schema::create('aditionals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('source_id')->default(1); // 1 Visit 2 CMS 3 Api External
            $table->foreignId('visit_id')->nullable()->constrained('visits')->cascadeOnDelete();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete();
            $table->foreignId('location_id')->constrained('locations')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->integer('type'); // 1-Aditional 2 Payment 3-Competence
            $table->foreignId('media_id')->nullable()->constrained('media')->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->timestamp('observed_at');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['business_id', 'observed_at']);
            $table->index(['location_id', 'observed_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exhibitions');
        Schema::dropIfExists('aditionals');
    }
};
