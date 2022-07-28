<?php

use App\Models\AdminLoginUrl;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Spatie\Permission\Models\Role;

$enableViews = config('fortify.views', true);


Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\RedirectUserController::class,'authenticateRedirect'])->name('dashboard');
});

    /***********************************************************
    | Admin login and dashboard Route
     ***********************************************************/
$admin_login_url = '';
if (Schema::hasTable('admin_login_urls')){
    $admin_login_url = AdminLoginUrl::first();
}

Route::get($admin_login_url ? $admin_login_url->admin_login_url : 'admin', [\App\Http\Controllers\Admin\AdminController::class, 'login'])->name('admin.login');

$role = '';
if (Schema::hasTable('roles')){
    $roles = Role::where('name', '!=', 'user')->pluck('name');
    if ($roles->count() > 0){
        $array = json_decode($roles, true);
        $array = array_values($array);
        $role = implode('|', $array);
    }
}

Route::middleware(['auth:web',config('jetstream.auth_session'),'verified','role:'.$role])->group(callback: function () {
    Route::get('/admin/dashboard', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/profile', [\App\Http\Controllers\Admin\AdminController::class, 'show'])->name('admin.profile.show');
});

/**********************************************************
| Permission Base Routes
 **********************************************************/
Route::group(['as' => 'admin.', 'prefix' => 'admin'], function(){
    /** User Routes **/
    Route::get('users/edit/{id}', [\App\Http\Controllers\Admin\UsersController::class, 'edit'])->name('user.edit')->middleware('role_or_permission:admin|edit user');
    Route::get('users/manage', [\App\Http\Controllers\Admin\UsersController::class, 'index'])->name('user.index')->middleware('role_or_permission:admin|view users');
    Route::get('user/create', [\App\Http\Controllers\Admin\UsersController::class, 'create'])->name('user.create')->middleware('role_or_permission:admin|create user');
    Route::post('user/store', [\App\Http\Controllers\Admin\UsersController::class, 'store'])->name('user.store')->middleware('role_or_permission:admin|create user');
    Route::put('users/update/{id}', [\App\Http\Controllers\Admin\UsersController::class, 'update'])->name('user.update')->middleware('role_or_permission:admin|edit user');
    Route::delete('users/delete/{id}', [\App\Http\Controllers\Admin\UsersController::class, 'destroy'])->name('user.destroy')->middleware('role_or_permission:admin|delete user');



    /** Role Routes **/
    Route::get('roles/manage', [\App\Http\Controllers\Admin\MembersController::class,'index'])->name('role.index')->middleware('role_or_permission:admin|view roles');
    Route::get('role/create', [\App\Http\Controllers\Admin\MembersController::class,'create'])->name('role.create')->middleware('role_or_permission:admin|create role');
    Route::post('role/create', [\App\Http\Controllers\Admin\MembersController::class,'store'])->name('role.store')->middleware('role_or_permission:admin|create role');
    Route::get('role/edit/{id}', [\App\Http\Controllers\Admin\MembersController::class,'edit'])->name('role.edit')->middleware('role_or_permission:admin|edit role');
    Route::put('role/update/{id}', [\App\Http\Controllers\Admin\MembersController::class,'update'])->name('role.update')->middleware('role_or_permission:admin|edit role');
    Route::delete('role/destroy/{id}', [\App\Http\Controllers\Admin\MembersController::class,'destroy'])->name('role.destroy')->middleware('role_or_permission:admin|delete role');

    /** Category Routes **/
    Route::get('categories/manage', [\App\Http\Controllers\Admin\Post\CategoryController::class,'index'])->name('category.index')->middleware('role_or_permission:admin|view categories');
    Route::get('category/create', [\App\Http\Controllers\Admin\Post\CategoryController::class,'create'])->name('category.create')->middleware('role_or_permission:admin|create category');
    Route::get('category/edit/{id}', [\App\Http\Controllers\Admin\Post\CategoryController::class,'edit'])->name('category.edit')->middleware('role_or_permission:admin|edit category');
    Route::get('category/trashed', [\App\Http\Controllers\Admin\Post\CategoryController::class,'trashed'])->name('category.trashed')->middleware('role_or_permission:admin|delete category');
    Route::get('category/restore/{id}', [\App\Http\Controllers\Admin\Post\CategoryController::class,'restore'])->name('category.restore')->middleware('role_or_permission:admin|delete category');
    Route::post('category/create', [\App\Http\Controllers\Admin\Post\CategoryController::class,'store'])->name('category.store')->middleware('role_or_permission:admin|create category');
    Route::put('category/update/{id}', [\App\Http\Controllers\Admin\Post\CategoryController::class,'update'])->name('category.update')->middleware('role_or_permission:admin|edit category');
    Route::delete('category/destroy/{id}', [\App\Http\Controllers\Admin\Post\CategoryController::class,'destroy'])->name('category.destroy')->middleware('role_or_permission:admin|delete category');
    Route::delete('category/trashed-delete/{id}', [\App\Http\Controllers\Admin\Post\CategoryController::class,'permanentlyDestroy'])->name('category.permanently.destroy')->middleware('role_or_permission:admin|delete category');

    /** Posts Routes **/
    Route::get('posts/manage', [\App\Http\Controllers\Admin\Post\PostController::class,'index'])->name('post.index')->middleware('role_or_permission:admin|view posts');
    Route::get('post/create', [\App\Http\Controllers\Admin\Post\PostController::class,'create'])->name('post.create')->middleware('role_or_permission:admin|create post');
    Route::get('post/edit/{id}', [\App\Http\Controllers\Admin\Post\PostController::class,'edit'])->name('post.edit')->middleware('role_or_permission:admin|edit post');
    Route::get('post/trashed', [\App\Http\Controllers\Admin\Post\PostController::class,'trashed'])->name('post.trashed')->middleware('role_or_permission:admin|delete post');
    Route::get('post/restore/{id}', [\App\Http\Controllers\Admin\Post\PostController::class,'restore'])->name('post.restore')->middleware('role_or_permission:admin|delete post');
    Route::post('post/store', [\App\Http\Controllers\Admin\Post\PostController::class,'store'])->name('post.store')->middleware('role_or_permission:admin|create post');
    Route::post('post/update/{id}', [\App\Http\Controllers\Admin\Post\PostController::class,'update'])->name('post.update')->middleware('role_or_permission:admin|edit post');
    Route::put('post/draft-update/{id}', [\App\Http\Controllers\Admin\Post\PostController::class,'draftUpdate'])->name('post.draft.update')->middleware('role_or_permission:admin|edit post');
    Route::delete('post/destroy/{id}', [\App\Http\Controllers\Admin\Post\PostController::class,'destroy'])->name('post.destroy')->middleware('role_or_permission:admin|delete post');
    Route::delete('post/trashed-delete/{id}', [\App\Http\Controllers\Admin\Post\PostController::class,'permanentlyDestroy'])->name('post.permanently.destroy')->middleware('role_or_permission:admin|delete post');

    Route::get('post-review/{slug}', [\App\Http\Controllers\Admin\Post\PostController::class, 'review'])->name('post.review')->middleware('role_or_permission:admin|view posts');

    /** Security Routes **/
    Route::get('security/limit-login-attempt', [\App\Http\Controllers\Admin\Security\LimitLoginAttemptController::class,'index'])->name('limit.login.index')->middleware('role_or_permission:admin');
    Route::put('security/limit-login-attempt/{id}', [\App\Http\Controllers\Admin\Security\LimitLoginAttemptController::class,'update'])->name('limit.login.update')->middleware('role_or_permission:admin');
    Route::get('security/brute-force/admin-login-url', [\App\Http\Controllers\Admin\Security\BruteForceController::class,'index'])->name('brute.force.index')->middleware('role_or_permission:admin');
    Route::put('security/brute-force/admin-login-url/{id}', [\App\Http\Controllers\Admin\Security\BruteForceController::class,'update'])->name('brute.force.admin.login.url.update')->middleware('role_or_permission:admin');

    /** Backup Routes **/
    Route::get('backup', [\App\Http\Controllers\Admin\Backup\BackupController::class,'index'])->name('backup.index')->middleware('role_or_permission:admin');
    Route::get('backup/settings', [\App\Http\Controllers\Admin\Backup\BackupController::class,'settings'])->name('backup.settings')->middleware('role_or_permission:admin');

    /** Dropbox Routes **/
    Route::get('backup/file-delete', [\App\Http\Controllers\Admin\Backup\DropboxAppController::class,'softDelete'])->name('backup.destroy')->middleware('role_or_permission:admin');
    Route::get('backup/trash', [\App\Http\Controllers\Admin\Backup\DropboxAppController::class,'trash'])->name('backup.trash')->middleware('role_or_permission:admin');
    Route::get('backup/restore', [\App\Http\Controllers\Admin\Backup\DropboxAppController::class,'restore'])->name('backup.restore')->middleware('role_or_permission:admin');
    Route::get('backup/start', [\App\Http\Controllers\Admin\Backup\DropboxAppController::class,'backup'])->name('backup.start')->middleware('role_or_permission:admin');
    Route::post('/dropbox/settings', [\App\Http\Controllers\Admin\Backup\DropboxAppController::class,'store'])->name('dropbox.store')->middleware('role_or_permission:admin');
    Route::get('/dropbox/authorize-token', [\App\Http\Controllers\Admin\Backup\DropboxAppController::class, 'token'])->middleware('role_or_permission:admin');
    Route::get('/dropbox/authorize-complete', [\App\Http\Controllers\Admin\Backup\DropboxAppController::class, 'authorizeComplete'])->name('backup.authorize')->middleware('role_or_permission:admin');

    /** Schedule Routes **/
    Route::get('schedule-calender', [\App\Http\Controllers\Admin\Schedule\ScheduleController::class, 'calender'])->name('schedule.index')->middleware('role_or_permission:admin');
    Route::get('schedule-settings', [\App\Http\Controllers\Admin\Schedule\ScheduleController::class, 'settings'])->name('schedule.settings')->middleware('role_or_permission:admin');
    Route::get('schedule-run', [\App\Http\Controllers\Admin\Schedule\ScheduleController::class, 'run'])->name('schedule.run')->middleware('role_or_permission:admin');
    Route::post('schedule-store', [\App\Http\Controllers\Admin\Schedule\ScheduleController::class, 'store'])->name('schedule.store')->middleware('role_or_permission:admin');

    /** Mail Setting Route **/
    Route::get('mail-settings', [\App\Http\Controllers\Admin\Mail\MailSettingController::class, 'setting'])->name('mail.setting')->middleware('role_or_permission:admin');
    Route::put('mail-settings/update/{id}', [\App\Http\Controllers\Admin\Mail\MailSettingController::class, 'update'])->name('mail.update')->middleware('role_or_permission:admin');

    /** Maintenance Setting Route **/
    Route::get('maintenance', [\App\Http\Controllers\Admin\MaintenanceController::class, 'index'])->name('maintenance.index')->middleware('role_or_permission:admin');
    Route::get('maintenance-status/{status}', [\App\Http\Controllers\Admin\MaintenanceController::class, 'settings'])->name('maintenance.setting')->middleware('role_or_permission:admin');

    /** Privacy Policy page Route **/
    Route::get('privacy-policy-page', [\App\Http\Controllers\Admin\AdminController::class, 'privacyPolicy'])->name('privacy.policy')->middleware('role_or_permission:admin');
    Route::post('privacy-policy-page', [\App\Http\Controllers\Admin\AdminController::class, 'privacyPolicyStore'])->name('privacy.policy.store')->middleware('role_or_permission:admin');

});

Route::post('/upload',[\App\Http\Controllers\Admin\TynimceController::class, 'upload'])->middleware(['role_or_permission:admin|edit post|edit others post']);


/***********************************************************
| Redirect user dashboard route
 ***********************************************************/
Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified','role:user'])->group(function () {
    Route::get('/user/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('user.dashboard');
});


/***********************************************************
| Admin password reset route
 ***********************************************************/
if (Features::enabled(Features::resetPasswords())) {
    if ($enableViews) {
        Route::get('admin/forgot-password', [\App\Http\Controllers\Admin\AdminResetPasswordController::class, 'create'])
            ->middleware(['guest:'.config('fortify.guard')])
            ->name('admin.password.request');

        Route::get('admin/reset-password/{token}', [\App\Http\Controllers\Admin\AdminNewPasswordController::class, 'create'])
            ->middleware(['guest:'.config('fortify.guard')])
            ->name('admin.password.reset');
    }

    Route::post('admin/forgot-password', [\App\Http\Controllers\Admin\AdminResetPasswordController::class, 'store'])
        ->middleware(['guest:'.config('fortify.guard')])
        ->name('admin.password.email');

    Route::post('admin/reset-password', [\App\Http\Controllers\Admin\AdminNewPasswordController::class, 'store'])
        ->middleware(['guest:'.config('fortify.guard')])
        ->name('admin.password.update');
}

Route::post('/login', [\App\Http\Controllers\AuthenticatedSessionController::class, 'store'])->middleware(array_filter([
        'guest:'.config('fortify.guard'),
        'throttle:api',
    ]));
Route::post('/logout', [\App\Http\Controllers\AuthenticatedSessionController::class, 'destroy'])->name('logout');
/***********************************************************
| Frontend routes
 ***********************************************************/
Route::get('/', [\App\Http\Controllers\HomeController::class, 'home'])->name('home');
Route::get('post/{slug}', [\App\Http\Controllers\PostController::class, 'view'])->name('post.view');
Route::get('category/{slug}', [\App\Http\Controllers\PostController::class, 'categoryPost'])->name('category.post');
Route::get('/privacy', [\App\Http\Controllers\HomeController::class, 'privacy'])->name('page.privacy');




