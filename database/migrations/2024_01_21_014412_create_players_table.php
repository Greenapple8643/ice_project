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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('playerid')->unique();
            $table->uuid('accesscode')->unique();

            // $table->string('playerid');
            $table->unsignedInteger('org_id');
            $table->string('first');
            $table->string('last');
            $table->string('full_name')->virtualAs('concat(first, \' \', last)');
            $table->string('label')->virtualAs('concat(playerid, \' (\', first, \' \', last, \')\')');

            $table->string('emails');
            $table->date('dob');
            // $table->string('registered_position')->nullable();   // Player|Goalie|Player/Goalie
            
            $table->unsignedInteger('season_id');
            // $table->unsignedInteger('league_id');
            // $table->unsignedInteger('division_id');

            $table->integer('status');

            
            // $table->string('defence')->nullable();                         // Yes | No
            // $table->string('volunteer')->nullable();               // Yes | No | Maybe
            // $table->string('prefered_position')->nullable();       // Forward | Defence | None
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};