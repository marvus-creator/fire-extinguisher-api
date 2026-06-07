<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExtinguisherController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\MaintenanceLogController;
use App\Http\Controllers\ReportController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:api')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::put('/change-password', [AuthController::class, 'changePassword']);

    // Extinguishers
    Route::get('/extinguishers', [ExtinguisherController::class, 'index']);
    Route::post('/extinguishers', [ExtinguisherController::class, 'store']);
    Route::get('/extinguishers/{id}', [ExtinguisherController::class, 'show']);
    Route::put('/extinguishers/{id}', [ExtinguisherController::class, 'update']);
    Route::delete('/extinguishers/{id}', [ExtinguisherController::class, 'destroy']);

    // Inspections
    Route::get('/inspections', [InspectionController::class, 'index']);
    Route::post('/inspections', [InspectionController::class, 'store']);
    Route::get('/inspections/{id}', [InspectionController::class, 'show']);
    Route::put('/inspections/{id}', [InspectionController::class, 'update']);
    Route::delete('/inspections/{id}', [InspectionController::class, 'destroy']);

    // Maintenance Logs
    Route::get('/maintenance-logs', [MaintenanceLogController::class, 'index']);
    Route::post('/maintenance-logs', [MaintenanceLogController::class, 'store']);
    Route::get('/maintenance-logs/{id}', [MaintenanceLogController::class, 'show']);
    Route::put('/maintenance-logs/{id}', [MaintenanceLogController::class, 'update']);
    Route::delete('/maintenance-logs/{id}', [MaintenanceLogController::class, 'destroy']);

    // Reports
    Route::get('/reports/general', [ReportController::class, 'generalReport']);
    Route::get('/reports/maintenance-history', [ReportController::class, 'maintenanceHistory']);
    Route::get('/reports/expired', [ReportController::class, 'expiredExtinguishers']);

    // Export
    Route::get('/reports/export/csv', [ReportController::class, 'exportCSV']);
    Route::get('/reports/export/pdf', [ReportController::class, 'exportPDF']);
});