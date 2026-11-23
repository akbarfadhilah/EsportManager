<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\PlayerImportController;
use App\Http\Controllers\EsportTeamController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\HeroController;

Route::get('/', function () {
    return redirect()->route('players.index');
});

Route::resource('regions', RegionController::class);
Route::get('players/import', [PlayerImportController::class, 'create'])->name('players.import');
Route::post('players/import/preview', [PlayerImportController::class, 'preview'])->name('players.import.preview');
Route::post('players/import/commit', [PlayerImportController::class, 'store'])->name('players.import.store');
Route::post('players/import/cancel', [PlayerImportController::class, 'cancel'])->name('players.import.cancel');
Route::resource('players', PlayerController::class);
Route::resource('teams', EsportTeamController::class);
Route::resource('heroes', HeroController::class);
Route::post('tournaments/{tournament}/complete', [TournamentController::class, 'complete'])->name('tournaments.complete');
Route::post('tournaments/{tournament}/grand-final', [TournamentController::class, 'grandFinal'])->name('tournaments.grandFinal');
Route::resource('tournaments', TournamentController::class);

// Match routes (schedule is auto-generated when a tournament is created)
Route::get('tournaments/{tournament}/matches', [MatchController::class, 'index'])->name('matches.index');
Route::get('matches/{match}', [MatchController::class, 'show'])->name('matches.show');
Route::post('matches/{match}/record-round', [MatchController::class, 'recordRound'])->name('matches.recordRound');
Route::delete('matches/{match}', [MatchController::class, 'destroy'])->name('matches.destroy');

// Leaderboard routes
Route::get('tournaments/{tournament}/leaderboard', [LeaderboardController::class, 'show'])->name('leaderboard.show');
Route::get('tournaments/{tournament}/leaderboard/players', [LeaderboardController::class, 'playerContribution'])->name('leaderboard.playerContribution');
Route::get('tournaments/{tournament}/teams/{team}/history', [LeaderboardController::class, 'teamHistory'])->name('leaderboard.teamHistory');
