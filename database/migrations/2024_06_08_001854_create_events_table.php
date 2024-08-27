<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('org_id');
            $table->date('date');
            $table->unsignedBigInteger('location_id')->nullable();
            $table->string('location_external')->nullable();
            $table->decimal('cost', 8, 2)->nullable();
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedBigInteger('type_id');
            // $table->foreignId('booking_id')->nullable()->constrained('bookings');
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->string('status');
            $table->unsignedBigInteger('home_team_id')->nullable();
            $table->unsignedBigInteger('away_team_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
