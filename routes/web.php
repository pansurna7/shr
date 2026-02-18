<?php

use App\Http\Controllers\BackendDashboardController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\FrontEnd\SubmissionTimeController;
use App\Http\Controllers\FrontEnd\SubmissionCutiController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\Holidaycontroller;
use App\Http\Controllers\FrontEnd\SubmissionSakitController;
use App\Http\Controllers\WorkingHoursController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\BuilderControler;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\FrontEnd\DashboardController;
use App\Http\Controllers\FrontEnd\PresensiController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\permissionController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Models\Menu;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FrontEnd\MonitoringController;
use App\Http\Controllers\FrontEnd\SubmissionIzinController;
use App\Http\Controllers\WorkingHoursDeptController;
use App\Models\WorkingHours;
use App\Http\Controllers\AnnouncementController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboards', function () {
    return view('backend.dashboard');
})
    ->middleware(['auth', 'verified'])
    ->name('dashboards');

Route::middleware('auth')->group(function () {
    route::get('/dashboards', [BackendDashboardController::class, 'index'])->name('dashboards');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/{id}', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'can:permissions.permission'])->group(function () {
    Route::get('/permissions', [permissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/create', [permissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions/store', [permissionController::class, 'store'])->name('permissions.store');
    Route::get('/permissions/edit/{id}', [permissionController::class, 'edit'])->name('permissions.edit');
    Route::post('/permissions/update/{id}', [permissionController::class, 'update'])->name('permissions.update');
    Route::post('/permissions/delete/{id}', [permissionController::class, 'delete'])->name('permissions.delete');
});

Route::middleware(['auth', 'can:roles.permission'])->group(function () {
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles/store', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/show', [RoleController::class, 'show'])->name('roles.show');
    Route::get('/roles/edit/{id}', [RoleController::class, 'edit'])->name('roles.edit');
    Route::post('/roles/update/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::post('/roles/delete/{id}', [RoleController::class, 'delete'])->name('roles.delete');
});

Route::middleware(['auth', 'can:users.permission'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::post('/users/update/{id}', [UserController::class, 'update'])->name('users.update');
    Route::post('/users/delete/{id}', [UserController::class, 'delete'])->name('users.delete');
    Route::patch('/users/{id}/theme', [UserController::class, 'updateUserTheme'])->name('users.theme.update');
});

Route::middleware(['auth', 'can:settings.permission'])->group(function () {
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/update', [SettingController::class, 'update'])->name('settings.update');
});
Route::middleware(['auth', 'can:menus.permission'])->group(function () {
    Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');
    Route::get('/menus/create', [MenuController::class, 'create'])->name('menus.create');
    Route::post('/menus/store', [MenuController::class, 'store'])->name('menus.store');
    Route::get('/menus/edit/{id}', [MenuController::class, 'edit'])->name('menus.edit');
    Route::post('/menus/update/{id}', [MenuController::class, 'update'])->name('menus.update');
    Route::post('/menus/delete/{id}', [MenuController::class, 'delete'])->name('menus.delete');
});

Route::group(['as' => 'menus.', 'prefix' => 'menus/{id}'], function () {
    Route::post('/order', [BuilderControler::class, 'order'])->name('builder.order');
    Route::get('/builder', [BuilderControler::class, 'index'])->name('builder.index');

    Route::get('/item/create', [BuilderControler::class, 'itemCreate'])->name('item.create');
    Route::post('/item/store', [BuilderControler::class, 'itemStore'])->name('item.store');
    Route::get('/item/edit/{itemId}', [BuilderControler::class, 'itemEdit'])->name('item.edit');
    Route::put('/item/update/{itemId}', [BuilderControler::class, 'itemUpdate'])->name('item.update');
    Route::post('/item/delete/{itemId}', [BuilderControler::class, 'itemDelete'])->name('item.delete');
});

// employee

Route::middleware(['auth', 'can:branches.permission'])->group(function () {
    Route::get('/branches', [BranchController::class, 'index'])->name('branch.index');
    Route::post('/branch/store', [BranchController::class, 'store'])->name('branch.store');
    Route::post('/branch/edit', [BranchController::class, 'edit'])->name('branch.edit');
    Route::put('/branch/update/{id}', [BranchController::class, 'update'])->name('branch.update');
    Route::post('/branch/delete/{id}', [BranchController::class, 'delete'])->name('branch.delete');
});
Route::middleware(['auth', 'can:locations.permission'])->group(function () {
    Route::get('/locations', [LocationController::class, 'index'])->name('location.index');
    Route::post('/location/store', [LocationController::class, 'store'])->name('location.store');
    Route::get('/location/edit/{id}', [LocationController::class, 'edit'])->name('location.edit');
    Route::put('/location/update/{id}', [LocationController::class, 'update'])->name('location.update');
    Route::delete('/location/delete/{id}', [LocationController::class, 'destroy'])->name('location.destroy');
});

Route::middleware(['auth', 'can:announcements.permission'])->group(function () {
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('location.index');
    Route::post('/announcement/store', [AnnouncementController::class, 'store'])->name('announcement.store');
    Route::get('/announcement/edit/{id}', [AnnouncementController::class, 'edit'])->name('announcement.edit');
    Route::put('/announcement/update/{id}', [AnnouncementController::class, 'update'])->name('announcement.update');
    Route::delete('/announcement/delete/{id}', [AnnouncementController::class, 'delete'])->name('announcement.delete');
});

Route::middleware(['auth', 'can:departements.permission'])->group(function () {
    Route::get('/departements', [DepartementController::class, 'index'])->name('departement.index');
    // Route::get('/departement/create',[DepartementController::class,'create'])->name('departement.create');
    Route::post('/departement/store', [DepartementController::class, 'store'])->name('departement.store');
    Route::post('/departement/edit', [DepartementController::class, 'edit'])->name('departement.edit');
    Route::put('/departement/update/{id}', [DepartementController::class, 'update'])->name('departement.update');
    Route::post('/departement/delete/{id}', [DepartementController::class, 'delete'])->name('departement.delete');
});

Route::middleware(['auth', 'can:positions.permission'])->group(function () {
    Route::get('/positions', [PositionController::class, 'index'])->name('position.index');
    // Route::get('/position/create',[PositionController::class,'create'])->name('position.create');
    Route::post('/position/store', [PositionController::class, 'store'])->name('position.store');
    Route::post('/position/edit', [PositionController::class, 'edit'])->name('position.edit');
    Route::put('/position/update/{id}', [PositionController::class, 'update'])->name('position.update');
    Route::post('/position/delete/{id}', [PositionController::class, 'delete'])->name('position.delete');
});

Route::middleware(['auth', 'can:employees.permission'])->group(function () {
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employee.index');
    Route::get('/employee/create', [employeeController::class, 'create'])->name('employee.create');
    Route::post('/employee/store', [EmployeeController::class, 'store'])->name('employee.store');
    Route::get('/employee/edit/{id}', [EmployeeController::class, 'edit'])->name('employee.edit');
    Route::post('/employee/update/{id}', [EmployeeController::class, 'update'])->name('employee.update');
    Route::post('/employee/delete/{id}', [EmployeeController::class, 'delete'])->name('employee.delete');
    // Route::get('/employee/time/{id}',[EmployeeController::class,'setWorkingHours'])->name('employee.time');
});

Route::middleware(['auth', 'can:monitorings.permission'])->group(function () {
    Route::get('/monitorings', [PresensiController::class, 'monitoring'])->name('monitoring.index');
    Route::post('/getpresensi', [PresensiController::class, 'getpresensi']);
    Route::post('/showmap', [PresensiController::class, 'showmap']);
});

Route::middleware(['auth', 'can:submissions.permission'])->group(function () {
    Route::get('/submissions', [PresensiController::class, 'submission'])->name('submission.index');
    Route::patch('/submissions/{id}/status', [PresensiController::class, 'updateStatus'])->name('submissions.update_status');
    Route::post('/showmap', [PresensiController::class, 'showmap']);
});

Route::middleware(['auth', 'can:leaves.permission'])->group(function () {
    Route::get('/leaves', [LeaveController::class, 'index'])->name('leave.index');
    Route::post('/leave/store', [LeaveController::class, 'store'])->name('leave.store');
    Route::post('/leave/edit', [LeaveController::class, 'edit'])->name('leave.edit');
    Route::put('/leave/update/{id}', [LeaveController::class, 'update'])->name('leave.update');
    Route::post('/leave/delete/{id}', [LeaveController::class, 'delete'])->name('leave.delete');
});
// Holidys
Route::middleware(['auth', 'can:holidays.permission'])->group(function () {
    Route::get('/holidays', [HolidayController::class, 'index'])->name('holiday.index');
    Route::post('/holiday/store', [HolidayController::class, 'store'])->name('holiday.store');
    Route::post('/holiday/edit', [HolidayController::class, 'edit'])->name('holiday.edit');
    Route::put('/holiday/update/{id}', [HolidayController::class, 'update'])->name('holiday.update');
    Route::post('/holiday/delete/{id}', [HolidayController::class, 'delete'])->name('holiday.delete');
    Route::get('/holiday/download', [HolidayController::class, 'download'])->name('holiday.download-template');
    Route::post('/holiday/import', [HolidayController::class, 'importExcel'])->name('holiday.import');
});

Route::middleware(['auth', 'can:reportpresences.permission'])->group(function () {
    Route::get('/reportpresences', [PresensiController::class, 'reportPresence'])->name('reportPresence.index');
    Route::post('/cetakreportemployee', [PresensiController::class, 'printReportPerEmployee'])->name('report.cetak');
});

Route::middleware(['auth', 'can:recaps.permission'])->group(function () {
    Route::get('/recaps', [PresensiController::class, 'rekapPresence'])->name('rekapPresence.index');
    Route::post('/cetakrekap', [PresensiController::class, 'cetakrekap'])->name('rekap.cetak');
});

Route::middleware(['auth', 'can:workinghours.permission'])->group(function () {
    Route::get('/workinghours', [WorkingHoursController::class, 'index'])->name('workinghour.index');
    Route::get('/workinghour/create', [WorkingHoursController::class, 'create'])->name('workinghour.create');
    Route::post('/workinghour/store', [WorkingHoursController::class, 'store'])->name('workinghour.store');
    Route::post('/workinghour/edit', [WorkingHoursController::class, 'edit'])->name('workinghour.edit');
    Route::put('/workinghour/update/{id}', [WorkingHoursController::class, 'update'])->name('workinghour.update');
    Route::post('/workinghour/delete/{id}', [WorkingHoursController::class, 'delete'])->name('workinghour.delete');
});

Route::middleware(['auth', 'can:workinghoursdepts.permission'])->group(function () {
    Route::get('/workinghoursdepts', [WorkingHoursDeptController::class, 'index'])->name('whd.index');
    Route::get('/workinghoursdepts/create', [WorkingHoursDeptController::class, 'create'])->name('whd.create');
    Route::Post('/workinghoursdepts/store', [WorkingHoursDeptController::class, 'store'])->name('whd.store');
    Route::get('/workinghoursdepts/edit/{id}', [WorkingHoursDeptController::class, 'edit'])->name('whd.edit');
    Route::get('/workinghoursdepts/show/{id}', [WorkingHoursDeptController::class, 'show'])->name('whd.show');
    Route::Post('/workinghoursdepts/update/{id}', [WorkingHoursDeptController::class, 'update'])->name('whd.update');
    Route::Post('/workinghoursdepts/delete/{id}', [WorkingHoursDeptController::class, 'delete'])->name('whd.delete');
});

// FrontEnd Presensi
Route::get('/frontend/dashboards', function () {
    return view('frontend.index');
})
    ->middleware(['auth', 'verified'])
    ->name('frontend.dashboards');
Route::get('/download-announcement/{filename}', [DashboardController::class, 'downloadFile'])->name('download.announcement');
Route::middleware(['auth'])->group(function () {
    Route::get('/frontend/dashboards', [DashboardController::class, 'index'])->name('frontend.dashboards');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/presensi/create', [PresensiController::class, 'create'])->name('presensi.create');
    Route::post('/presensi/store', [PresensiController::class, 'store'])->name('presensi.store');

    Route::get('/editprofile', [PresensiController::class, 'editProfile'])->name('edit.profile');
    Route::post('updateprofile/{id}', [PresensiController::class, 'updateProfile'])->name('update.profile');

    Route::get('/presensi/history', [PresensiController::class, 'history'])->name('presensi.history');
    Route::post('/gethistory', [PresensiController::class, 'getHistory'])->name('get.history');

    // izin
    Route::get('presensi/izin', [PresensiController::class, 'izin'])->name('presensi.izin');
    Route::get('presensi/pengajuan', [PresensiController::class, 'pengajuan'])->name('presensi.pengajuan');
    // Route::post('/presensi/storeizin',[PresensiController::class,'storeizin'])->name('store.izin');
    Route::get('/submissions/izin', [SubmissionIzinController::class, 'create'])->name('submission.izin');
    Route::post('/submissions/cektglpengajuan', [SubmissionIzinController::class, 'cektglpengajuan']);
    Route::post('/submissions/storeizin', [SubmissionIzinController::class, 'storeizin'])->name('submission.store');
    Route::get('/submissions/sakit', [SubmissionSakitController::class, 'create'])->name('submission.sakit');
    Route::post('/submissions/storesakit', [SubmissionSakitController::class, 'storesakit'])->name('submissions.storesakit');
    Route::get('/submissions/cuti', [SubmissionCutiController::class, 'create'])->name('submission.cuti');
    Route::post('/submissions/storecuti', [SubmissionCutiController::class, 'storecuti'])->name('submission.storecuti');

    // koreksi jam absensi
    Route::get('/submissions/time', [SubmissionTimeController::class, 'create'])->name('submission.time');
    Route::post('/submissions/storetime', [SubmissionTimeController::class, 'storeKoreksi'])->name('submission.store_koreksi');
    Route::post('/submissions/cektanggaltime', [SubmissionTimeController::class, 'cektglpengajuan']);
});

require __DIR__ . '/auth.php';
