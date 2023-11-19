<?php

use Illuminate\Support\Facades\Route;
use Leaguesim\System\Controllers\SystemController;


Route::get('ping', [SystemController::class, 'ping']);


Route::get('reset', [SystemController::class, 'reset']);
