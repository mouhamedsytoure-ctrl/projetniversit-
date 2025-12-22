<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('accueil')
        : redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/accueil', function () {
        return view('accueil');
    })->name('accueil');

    Route::get('/parametres', function () {
        return view('parametres');
    })->name('parametres');

    Route::get('/gestion-profil', function () {
        return view('gestion_profil');
    })->name('gestion.profil');
});

require __DIR__.'/auth.php';
