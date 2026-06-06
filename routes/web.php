<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/nos-formations', [HomeController::class, 'trainingsPage'])->name('trainings.page');
Route::get('/programme', [HomeController::class, 'programPage'])->name('program.page');
Route::get('/competence', [HomeController::class, 'skillsPage'])->name('skills.page');
Route::post('/register', [HomeController::class, 'register'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])->name('register');
Route::post('/register.php', [HomeController::class, 'register'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Admin routes
Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::get('/categories/create', [AdminController::class, 'createCategory'])->name('admin.categories.create');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
    Route::get('/categories/{category}/edit', [AdminController::class, 'editCategory'])->name('admin.categories.edit');
    Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('admin.categories.update');
    Route::patch('/categories/{category}/move-up', [AdminController::class, 'moveCategoryUp'])->name('admin.categories.move-up');
    Route::patch('/categories/{category}/move-down', [AdminController::class, 'moveCategoryDown'])->name('admin.categories.move-down');
    Route::delete('/categories/{category}', [AdminController::class, 'destroyCategory'])->name('admin.categories.destroy');

    Route::get('/trainings', [AdminController::class, 'trainings'])->name('admin.trainings');
    Route::get('/trainings/create', [AdminController::class, 'createTraining'])->name('admin.trainings.create');
    Route::post('/trainings', [AdminController::class, 'storeTraining'])->name('admin.trainings.store');
    Route::get('/trainings/{training}/edit', [AdminController::class, 'editTraining'])->name('admin.trainings.edit');
    Route::put('/trainings/{training}', [AdminController::class, 'updateTraining'])->name('admin.trainings.update');
    Route::delete('/trainings/{training}', [AdminController::class, 'destroyTraining'])->name('admin.trainings.destroy');
    Route::get('/registrations', [AdminController::class, 'registrations'])->name('admin.registrations');
    Route::patch('/registrations/{registration}/status', [AdminController::class, 'updateRegistrationStatus'])->name('admin.registrations.status');

    Route::get('/payments', [AdminController::class, 'payments'])->name('admin.payments');
    Route::post('/payments', [AdminController::class, 'storePayment'])->name('admin.payments.store');

    Route::get('/skills', [AdminController::class, 'skills'])->name('admin.skills');
    Route::post('/skills', [AdminController::class, 'storeSkill'])->name('admin.skills.store');
    Route::get('/skills/{skill}/edit', [AdminController::class, 'editSkill'])->name('admin.skills.edit');
    Route::put('/skills/{skill}', [AdminController::class, 'updateSkill'])->name('admin.skills.update');
    Route::delete('/skills/{skill}', [AdminController::class, 'destroySkill'])->name('admin.skills.destroy');

    Route::get('/bundles', [AdminController::class, 'bundles'])->name('admin.bundles');
    Route::get('/bundles/create', [AdminController::class, 'createBundle'])->name('admin.bundles.create');
    Route::post('/bundles', [AdminController::class, 'storeBundle'])->name('admin.bundles.store');
    Route::get('/bundles/{bundle}/edit', [AdminController::class, 'editBundle'])->name('admin.bundles.edit');
    Route::put('/bundles/{bundle}', [AdminController::class, 'updateBundle'])->name('admin.bundles.update');
    Route::delete('/bundles/{bundle}', [AdminController::class, 'destroyBundle'])->name('admin.bundles.destroy');
});

// Route de détails de formation
Route::get('/formations/{training}', [HomeController::class, 'showTraining'])->name('training.show');
Route::get('/packs/{bundle}', [HomeController::class, 'showBundle'])->name('bundle.show');

// Student Authentication & Dashboard
Route::get('/signup', [\App\Http\Controllers\StudentAuthController::class, 'showSignup'])->name('student.signup');
Route::post('/signup', [\App\Http\Controllers\StudentAuthController::class, 'signup'])->name('student.signup.post');
Route::get('/login', [\App\Http\Controllers\StudentAuthController::class, 'showLogin'])->name('student.login');
Route::post('/login', [\App\Http\Controllers\StudentAuthController::class, 'login'])->name('student.login.post');
Route::post('/logout', [\App\Http\Controllers\StudentAuthController::class, 'logout'])->name('student.logout');

Route::middleware(['web'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\StudentDashboardController::class, 'index'])->name('student.dashboard');
    Route::post('/dashboard/payments', [\App\Http\Controllers\StudentDashboardController::class, 'storePaymentDeclaration'])->name('student.payments.declare');
    Route::delete('/dashboard/registrations/{registration}', [\App\Http\Controllers\StudentDashboardController::class, 'destroyRegistration'])->name('student.registrations.destroy');
});
