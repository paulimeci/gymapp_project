<?php

use Illuminate\Support\Facades\Route;

Route::get('/home', function () {
    $user = auth()->user();


    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }
    if ($user->hasRole('manager')) {
        return redirect()->route('manager.dashboard');
    }
    // Sigurohu që këtu emri 'Agent' përputhet me atë në DB (Agjent apo Agent?)
    if ($user->hasRole('agent')) {
        return redirect()->route('agent.dashboard');
    }

    // Nëse përdoruesi nuk ka asnjë nga rolet e mësipërme,
    // dërgoje te faqja kryesore ose te një faqe që ekziston:
    return redirect()->route('home');
})->middleware(['auth'])->name('dashboard');


Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::get('/admin/dashboard', \App\Livewire\Admin\LiveAdminDashboard::class)
        ->middleware('role:admin')
        ->name('admin.dashboard');

    Route::get('admin/manage/kategorite',\App\Livewire\Admin\LiveKategoriteEUshtrimeve::class)->middleware('role:admin')->name('manage.kategorite');

});

require __DIR__.'/settings.php';
