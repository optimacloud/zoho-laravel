<?php

use Illuminate\Support\Facades\Route;

Route::get('oauth2callback', [\Optimacloud\Zoho\Http\Controllers\ZohoController::class, 'oauth2callback']);
