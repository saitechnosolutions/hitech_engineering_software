<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LRController;
use App\Http\Controllers\BomController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\ProcessTeamController;
use App\Http\Controllers\ProductionStageController;

Route::middleware('guest')->group(function () {

     Route::get('/', function () {
        return view('pages.index');
    })->name('login');

    Route::post('/user-login', [LoginController::class, 'login'])->name('user-login');
});


Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    // Users

    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::post('/store', [UserController::class, 'store'])->name('users.store');

    // Role

    Route::get('/roles', [RoleController::class, 'index']);
    Route::post('/roles/store', [RoleController::class, 'store'])->name('roles.store');
    Route::get('roles/{roleId}/give-permission',[RoleController::class, 'addPermissionToRole'])->name('roles.addPermissionToRole');
    Route::put('roles/{roleId}/give-permission',[RoleController::class, 'givePermissionToRole'])->name('roles.givePermissionToRole');


    // Category

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories/store', [CategoryController::class, 'store'])->name('categories.store');

    // Customers

    Route::get('/customers', [CustomerController::class, 'index']);
    Route::post('/customers/store', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/get-customer-details/{customer_id}', [CustomerController::class, 'getCustomerDetails']);

    // Employees

    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::post('/employees/store', [EmployeeController::class, 'store'])->name('employees.store');

    // Process Team

    Route::get('/process-team', [ProcessTeamController::class, 'index']);
    Route::post('/process-team/store', [ProcessTeamController::class, 'store'])->name('process-team.store');

    // Production Stages

    Route::get('/production-stages', [ProductionStageController::class, 'index']);
    Route::post('/production-stages/store', [ProductionStageController::class, 'store'])->name('production-stages.store');

    // Products

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/show/{id}', [ProductController::class, 'show'])->name('products.show');

    // BOM

    Route::get('/manage-bom', [BomController::class, 'index']);
    Route::get('/bom/create', [BomController::class, 'create'])->name('bom.create');
    Route::post('/bom/store', [BomController::class, 'store'])->name('bom.store');

    // Quotations
    Route::get('/quotations', [QuotationController::class, 'index']);
    Route::get('/quotations/create', [QuotationController::class, 'create'])->name('quotation.create');
    Route::post('/quotations/store', [QuotationController::class, 'store'])->name('quotation.store');
    Route::get('/quotationproduct/{productid}', [QuotationController::class, 'quotationProducts']);
    Route::get('/quotation_format/{id}', [QuotationController::class, 'quotationFormat']);
    Route::post('/quotations_batch/store', [QuotationController::class, 'storeQuotationBatch'])->name('quotation_batch.store');
    Route::post('/move_to_production', [QuotationController::class, 'moveToProduction']);
    Route::post('/productComplete', [ProductionController::class, 'productComplete']);

    // Production

    Route::get('/productions', [ProductionController::class, 'index']);
    Route::get('/production_view/{id}', [ProductionController::class, 'productionView']);
    Route::post('/update_quantity', [ProductionController::class, 'updateProductionStatus'])->name('production.updateStatus');
    Route::get('/getProductionDetails/{id}', [ProductionController::class, 'getProductionDetails']);
    Route::post('/productionUpdate', [ProductionController::class, 'productionUpdate']);
    Route::post('/allocateEmployee', [ProductionController::class, 'allocateEmployee'])->name('production.allocateEmployee');
    Route::get('/invoice-request/{quotation_id}', [ProductionController::class, 'invoiceRequest']);
    Route::post('/invoice-request-submit', [ProductionController::class, 'invoiceRequestSubmit'])->name('invoice.request');
    Route::get('/invoice-request-details', [ProductionController::class, 'invoiceRequestDetails']);
    Route::post('/invoice_status_completed', [ProductionController::class, 'invoiceStatusUpdate']);
    Route::get('/invoice-request-information/{request_id}', [ProductionController::class, 'invoiceRequestinformation']);
    Route::get('/active-order-details', [ProductionController::class, 'activeOrderDetails'])->name('active-order-details');

    Route::get('/permissions', [PermissionController::class, 'index']);
    Route::post('/permissions/store', [PermissionController::class, 'store'])->name('permission.store');

    // Components

    Route::get('/components', [ProductController::class, 'components'])->name('components');
    Route::post('/components/store', [ProductController::class, 'componentsStore'])->name('components.store');

    // LR Documents

    Route::get('/lr-documents', [LRController::class, 'index'])->name('lr-documents');
    Route::post('/lr-documents/store', [LRController::class, 'store'])->name('lr-documents.store');

    // Tasks

    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks');
    Route::get('/tasks/create-task', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks/store', [TaskController::class, 'store'])->name('tasks.store');
    Route::post('/tasks/update-status', [TaskController::class, 'updateTaskStatus'])->name('tasks.updatetaskstatus');

    // Payments

    Route::get('/payments', [PaymentController::class, 'index'])->name('payments');
    Route::post('/payments/store', [PaymentController::class, 'store'])->name('payments.store');

// Route::view('/quotations', 'pages.quotations.index');
// Route::view('/manage-bom', 'pages.bom.index');
Route::view('/reports', 'pages.reports.index');

}
);