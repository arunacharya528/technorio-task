<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post("/login", [AuthController::class, 'login']);

Route::get("/job", [JobController::class, 'index'])->name('job.index');
Route::get("/job/{job}", [JobController::class, 'show'])->name('job.show');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post("/job", [JobController::class, 'store'])->name('job.store')->middleware(["ability:admin,recruiter"]);
    Route::put("/job/{job}", [JobController::class, 'update'])->name('job.update')->middleware(["ability:admin,recruiter"]);
    Route::delete("/job/{job}", [JobController::class, 'destroy'])->name('job.destroy')->middleware(["ability:admin,recruiter"]);
});

