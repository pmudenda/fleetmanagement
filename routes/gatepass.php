<?php
Route::name('gate-pass.')->prefix('gate-pass')->middleware(['auth', 'is.active', 'change.password'])->group( function () {
    Route::get('/', \App\Livewire\GatePass\Index::class)->name('index');
    Route::get('/create', \App\Livewire\GatePass\Create::class)->name('create');
    Route::get('/unauthorised', \App\Livewire\GatePass\UnauthorisedIndex::class)->name('unauthorised');
    Route::get('/unchecked', \App\Livewire\GatePass\UncheckedIndex::class)->name('unchecked');
    Route::get('/all', \App\Livewire\GatePass\AllIndex::class)->name('all');
    Route::get('/{gatePass}', \App\Livewire\GatePass\Show::class)->name('show');
});