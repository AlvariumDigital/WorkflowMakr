<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('actions', 'ActionController');
Route::apiResource('scenarios', 'ScenarioController');
Route::apiResource('statuses', 'StatusController');
Route::apiResource('transitions', 'TransitionController');
