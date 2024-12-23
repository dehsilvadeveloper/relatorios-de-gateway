<?php

use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportStatusController;
use App\Http\Controllers\ReportTypeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*
|-------------------------------------------
| Application Routes
|-------------------------------------------
*/
Route::get('/', function () {
    return 'Welcome to the API.';
})->name('welcome');

Route::prefix('/report-statuses')->name('report-status.')->group(function () {
    Route::get('/', [ReportStatusController::class, 'index'])->name('index');
});

Route::prefix('/report-types')->name('report-type.')->group(function () {
    Route::get('/', [ReportTypeController::class, 'index'])->name('index');
});

Route::prefix('/reports')->name('report.')->group(function () {
    Route::post('/', [ReportController::class, 'create'])->name('create');
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::get('/{id}/download', [ReportController::class, 'download'])->name('download');
});