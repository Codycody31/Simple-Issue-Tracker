<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\IssueCommentsController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// If user is not logged in, redirect to login page
Route::middleware(['auth'])->group(function () {
    // Home, redirect to dashboard
    Route::get('/', function () {
        return redirect()->route('dashboard');
    })->name('home');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Departments
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::patch('/departments', [DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('/departments', [DepartmentController::class, 'delete'])->name('departments.destroy');
    
    // Tickets
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::patch('/users', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users', [UserController::class, 'delete'])->name('users.destroy');
    
    // Issues
    Route::get('/issues', [IssueController::class, 'index'])->name('issues.index');
    Route::post('/issues', [IssueController::class, 'store'])->name('issues.store');
    Route::get('/issues/{issue}', [IssueController::class, 'show'])->name('issues.show');
    Route::patch('/issues/{issueId}/status/{statusId}', [IssueController::class, 'updateStatus'])->name('issues.updateStatus');
    Route::delete('/issues', [IssueController::class, 'destroy'])->name('issues.destroy');

    // Issue Comments
    Route::post('/issues/{issueId}/comments', [IssueCommentsController::class, 'store'])->name('issues.comments.store');
    Route::patch('/issues/{issueId}/comments', [IssueCommentsController::class, 'update'])->name('issues.comments.update');
    Route::delete('/issues/{issueId}/comments', [IssueCommentsController::class, 'destroy'])->name('issues.comments.destroy');
    
    // Profile
    Route::get('/manage-account', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/manage-account', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/manage-account', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
