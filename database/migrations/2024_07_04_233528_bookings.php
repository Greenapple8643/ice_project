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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->integer('org_id');
            $table->boolean('used');
            $table->integer('program_id');
            $table->integer('league_id');
            $table->enum('status', ['Acquired','Returned','Deleted']);
            $table->double('cost');
            $table->integer('location_id');
            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedBigInteger('event_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('bookings');
    }
};
