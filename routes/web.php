<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Branch\BranchController;
use App\Http\Controllers\Branch\ProfileController;
use App\Http\Controllers\Branch\AttendanceController;
use App\Http\Controllers\Report\BranchReportController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ManageDashboardController;
use App\Http\Controllers\Admin\ProductDashboardController;
use App\Http\Controllers\Branch\BranchDashboardController;
use App\Http\Controllers\AdminReports\AdminReportController;
use App\Http\Controllers\Admin\BranchDashboardController as AdminBranchDashboardController;


Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');

    return '✅ All Laravel caches cleared successfully!';
});
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/otp-login', [LoginController::class, 'otpLogin'])->name('otp.login');
Route::post('/verify-otp', [LoginController::class, 'verifyOtp'])->name('verify.otp');





Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/admin-dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/manage', [AdminDashboardController::class, 'manage'])->name('manage');
    Route::get('/manage-branches', [AdminDashboardController::class, 'create_branches'])->name('manage-branches');
    Route::post('/add-branch', [AdminDashboardController::class, 'add_branches'])->name('add-branch');
    Route::get('/edit-branch/{id}', [AdminDashboardController::class, 'edit_branch'])->name('edit-branch');
    Route::put('/update-branch/{id}', [AdminDashboardController::class, 'update_branch'])->name('update-branch');
    Route::delete('/delete-branch/{id}', [AdminDashboardController::class, 'delete_branch'])->name('delete-branch');
    Route::get('/manage-staff', [ManageDashboardController::class, 'create_staff'])->name('create-staff');
    Route::put('/update-staff/{id}', [ManageDashboardController::class, 'update_staff'])->name('admin.update-staff');
    Route::post('/add-staff', [ManageDashboardController::class, 'store_staff'])->name('store-staff');
    Route::delete('/delete-staff/{id}', [ManageDashboardController::class, 'delete_staff'])->name('delete-staff');
    Route::get('/manage-service', [ManageDashboardController::class, 'manage_services'])->name('manage-service');
    Route::post('/add-service', [ManageDashboardController::class, 'add_service'])->name('add-service');
    Route::delete('/delete-service/{id}', [ManageDashboardController::class, 'delete_service'])->name('delete-service');
    Route::put('/update-service/{id}', [ManageDashboardController::class, 'update_service'])->name('update-service');
    Route::get('/manage-product', [ProductDashboardController::class, 'manage_product'])->name('manage-product');
    Route::post('/add-product', [ProductDashboardController::class, 'add_product'])->name('add-product');
    Route::delete('/delete-product/{id}', [ProductDashboardController::class, 'delete_product'])->name('delete-product');
    Route::put('/update-product/{id}', [ProductDashboardController::class, 'update_product'])->name('update-product');
});
Route::get('/admin/get-approved-emails', [AdminDashboardController::class, 'getApprovedEmails']);
Route::post('/admin/approve-otp-email', [AdminDashboardController::class, 'approveOtpEmail']);
Route::post('/admin/deny-email/{id}', [AdminDashboardController::class, 'denyEmail']);

// -------------------- Admin Report Routes --------------------
Route::prefix('AdminReports')->name('adminreports.')->group(function () {
    Route::get('/', [AdminReportController::class, 'allreport'])->name('index');
    Route::match(['get', 'post'], '/date-wise', [AdminReportController::class, 'dateWise'])->name('date');
    Route::match(['get', 'post'], '/chair-wise', [AdminReportController::class, 'chairWise'])->name('chair');
    Route::match(['get', 'post'], '/service-report', [AdminReportController::class, 'serviceReport'])->name('service');
    Route::match(['get', 'post'], '/product-wise', [AdminReportController::class, 'productReport'])->name('product');
    Route::match(['get', 'post'], '/branch-wise', [AdminReportController::class, 'BranchReport'])->name('branch');
});

Route::prefix('Branch')->group(function () {
    route::get('/branch-dashboard', [BranchDashboardController::class, 'branch_dashboard'])->name('branch.dashboard');
    Route::get('/branch/chairs-status', [BranchDashboardController::class, 'chair_status'])->middleware('auth')->name('branch.chairs');
    Route::get('/branch/get-services', [BranchDashboardController::class, 'getServices'])->name('getservices');
    Route::get('/branch/get-products', [BranchDashboardController::class, 'getProducts'])->name('getproducts');
    Route::get('/get-customer-details/{mobile}', [BranchDashboardController::class, 'getCustomerDetails']);
    Route::post('/branch/book-chair', [BranchDashboardController::class, 'bookChair'])->name('book-chair');
    // Route::post('/update-chair-status', [BranchDashboardController::class, 'updateStatus']);
    Route::post('/branch/book-appointment', [BranchDashboardController::class, 'bookAppointment'])->name('branch.book-appointment');
    Route::get('/branch/view-bill', [BranchDashboardController::class, 'viewBill'])->name('branch.view-bill');
    Route::get('/chair-appointment/{chair_id}', [BranchDashboardController::class, 'getChairAppointment'])->name('branch.chair-appointment');
    Route::post('pay-bill', [BranchDashboardController::class, 'payBill']);
    Route::get('/print-bill/{id}', [BranchDashboardController::class, 'printBill'])->name('branch.printBill');
    Route::get('/attendance', [AttendanceController::class, 'showAttendence'])->name('attendance');
    Route::post('checkin/{employee_id}', [AttendanceController::class, 'checkIn'])->name('branch.attendance.checkin');
    Route::post('checkout/{employee_id}', [AttendanceController::class, 'checkOut'])->name('branch.attendance.checkout');
    Route::post('leave/{employee_id}', [AttendanceController::class, 'markLeave'])->name('branch.attendance.leave');
});
Route::post('/Branch/appointment/cancel/{id}', [BranchDashboardController::class, 'cancel']);



//bill status check route
Route::get('/check-bill-status/{appointment_id}', [BranchController::class, 'checkBillStatus']);
Route::post('/update-chair-status/{chairId}', [BranchDashboardController::class, 'updateStatus']);
Route::post('/checkout', [BranchController::class, 'checkout']);
Route::get('/pending-bills', [BranchController::class, 'pendingBills']);
// web.php
Route::get('/waiting-list', [BranchController::class, 'showWaitingList'])->name('waiting.list');
Route::post('/branch/cancel-waiting/{id}', [BranchController::class, 'cancelWaiting']);
Route::get('/Branch/branch/get-waiting-list', [BranchController::class, 'getWaitingList']);
Route::get('/Branch/mobile-appointment/{mobile}', [BranchController::class, 'getAppointmentByMobile']);
Route::get('/Branch/pendingbill/{mobile}/{appointmentId}', [BranchController::class, 'getpendingbilldata']);



// Report Routes 
Route::prefix('Report')->group(function () {
    Route::get('/', [BranchReportController::class, 'report'])->name('reports.index');

    Route::match(['get', 'post'], '/reports/date-wise', [BranchReportController::class, 'dateWise'])->name('reports.date');

    Route::match(['get', 'post'], '/chair-wise', [BranchReportController::class, 'chairWise'])->name('reports.chair');

    Route::match(['get', 'post'], '/service-report', [BranchReportController::class, 'serviceReport'])->name('reports.service');

    Route::match(['get', 'post'], '/product-wise', [BranchReportController::class, 'productReport'])->name('reports.product');

    Route::match(['get', 'post'], '/staff-wise', [BranchReportController::class, 'staffReport'])->name('reports.staff');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/setting', [ProfileController::class, 'settingsPage'])->name('settings');
    Route::post('/update-profile', [ProfileController::class, 'updateProfile'])->name('updateProfile');
    Route::post('/update-address', [ProfileController::class, 'updateAddress'])->name('updateAddress');
    Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('changePassword');
    //password change
    Route::post('/change-password-otp', [ProfileController::class, 'changepasswordOtp'])->name('changePasswordOtp');
});
