<?php

use Illuminate\Support\Facades\Route;

// (very) simple "authentication"
Route::get('/auth', [\App\Http\Controllers\Controller::class, 'auth'])->name("auth");
Route::post('/auth', [\App\Http\Controllers\Controller::class, 'auth'])->name("do_auth");

// Dashboard with an overview of the current session
Route::get('/', [\App\Http\Controllers\Controller::class, 'dashboard'])->name("dashboard");

// Map of the current session
Route::get('/map', [\App\Http\Controllers\Controller::class, 'map'])->name("map");

// Charts of the current session
Route::get('/charts', [\App\Http\Controllers\Controller::class, 'charts'])->name("charts");

// Files for the current session
Route::get('/files', [\App\Http\Controllers\Controller::class, 'files'])->name("files");

// Global Settings
Route::get('/settings', [\App\Http\Controllers\Controller::class, 'settings'])->name("settings");

// Page to show all sessions and to select the session to work with
Route::get('/sessions', [\App\Http\Controllers\Controller::class, 'sessions'])->name("sessions");

// Exit
Route::get('/logout', [\App\Http\Controllers\Controller::class, 'logout'])->name("logout");

// Minimal 'web service' that received (and record!) realtime data
Route::get('/record', [\App\Http\Controllers\Controller::class, 'record'])->name("record");
