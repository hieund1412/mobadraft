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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Tên trận đấu');
            $table->date('game_date')->comment('Ngày diễn ra');
            $table->time('start_time')->comment('Thời gian bắt đầu');
            $table->time('end_time')->comment('Thời gian kết thúc');
            $table->integer('total_rounds')->nullable()->comment('Số vòng đấu');
            $table->integer('referee_id')->unsigned()->nullable()->comment('ID trọng tài');
            $table->integer('referee_name')->unsigned()->nullable()->comment('Tên trọng tài');
            $table->string('team_blue')->comment('Tên đội xanh');
            $table->string('team_red')->comment('Tên đội đỏ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
