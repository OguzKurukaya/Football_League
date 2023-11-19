<?php

use Illuminate\Support\Facades\Route;
use Leaguesim\League\Controller\FixtureController;
use Leaguesim\League\Controller\LeagueController;
use Leaguesim\League\Controller\TeamController;

Route::get('/teams', [TeamController::class, 'getAllTeams']);


Route::get('/league', [LeagueController::class, 'getLeague']);
Route::get('/prediction', [LeagueController::class, 'getPrediction']);



Route::get('/create_fixture', [FixtureController::class, 'generateFixture']);
Route::get('/play_next_week', [FixtureController::class, 'playNextWeek']);
Route::get('/play_all_weeks', [FixtureController::class, 'playAllWeek']);
Route::get('/all_weeks_matches', [FixtureController::class, 'getAllWeeksMatches']);
Route::get('/next_week_matches', [FixtureController::class, 'getNextWeekMatches']);


Route::post('/edit_match', [FixtureController::class, 'editMatch']);
