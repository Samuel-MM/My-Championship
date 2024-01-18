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
        Schema::create('championship_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('championship_id');
            $table->unsignedBigInteger('team_id');
            $table->integer('pro_goals');
            $table->integer('pro_goals_total');
            $table->integer('own_goals');
            $table->integer('own_goals_total');
            $table->integer('points');
            $table->boolean('winner');
            $table->string('opposing_team_id');
            $table->integer('place');
            $table->timestamps();

            $table->foreign('championship_id')
            ->references('id')
            ->on('championships');

            $table->foreign('team_id')
            ->references('id')
            ->on('teams');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('championship_histories');
    }
};
