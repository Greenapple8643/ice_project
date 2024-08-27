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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('org_id');                          // NMHA
            $table->text('abbr');
            $table->text('name');
            $table->unsignedBigInteger('league_id');           // HOUSE LEAGUE

            // $table->string('age_group');
            // $table->string('owner')->nullable();
            // $table->unsignedBigInteger('league_teamid')->nullable();           // HOUSE LEAGUE
            // $table->unsignedBigInteger('division_id')->nullable();         // U7
            // $table->unsignedBigInteger('division_teamid')->nullable();         // U7
            // $table->unsignedBigInteger('tier_id')->nullable();          // A
            // $table->unsignedBigInteger('pool_id')->nullable();          // POOL A
            // $table->integer('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
