<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('name', 55);
            $table->string('image_url')->nullable();
            $table->integer('power');
        });
        Schema::create('matches', function (Blueprint $table) {
            $table->id('match_id')->autoIncrement();
            $table->integer('week');
            $table->integer('home_team_id');
            $table->integer('away_team_id');
            $table->integer('home_team_score')->default(0);
            $table->integer('away_team_score')->default(0);
            $table->boolean('is_played')->default(false);

            $table->foreign('home_team_id')->references('id')->on('teams');
            $table->foreign('away_team_id')->references('id')->on('teams');
        });

        $teamData =
            [
                [
                    'name' => 'Chelsea',
                    'image_url' => 'Chelsea.png',
                    'power' => 7
                ],
                [
                    'name' => 'Liverpool',
                    'image_url' => 'Liverpool.png',
                    'power' => 3
                ],
                [
                    'name' => 'Manchester City',
                    'image_url' => 'ManchesterC.png',
                    'power' => 5
                ],
                [
                    'name' => 'Manchester United',
                    'image_url' => 'ManchesterU.png',
                    'power' => 1
                ]
            ];
        DB::table('teams')->insert(
            $teamData
        );

    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('matches');
        Schema::drop('teams');
    }
};
