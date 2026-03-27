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
        Schema::create('rounds', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->tinyInteger('round_index');
            $table->integer('id_game');
            $table->date('round_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('result_team_red');
            $table->integer('result_team_blue');
            $table->string('team_victory');
            $table->text('hero_pick_team_red');
            $table->text('hero_ban_team_red');
            $table->text('hero_pick_team_blue');
            $table->text('hero_ban_team_blue');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rounds');
    }
};
