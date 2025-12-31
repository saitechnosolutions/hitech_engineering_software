<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LRController;
use App\Http\Controllers\BomController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReportController;
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


Route::middleware('auth')->group(
    function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/payment-collection-today', [DashboardController::class, 'paymentCollectionToday'])->name('active-order-details');
        Route::get('/collection-pending', [DashboardController::class, 'collectionPending'])->name('collection-pending');
        Route::get('/revenue-details', [DashboardController::class, 'revenueDetails'])->name('revenue-details');
        Route::get('/payment-pending-details', [DashboardController::class, 'paymentPendingDetails'])->name('payment-pending-details');
        Route::get('/retail-active-orders', [DashboardController::class, 'retailActiveOrders'])->name('retail-active-orders');
        Route::get('/retail-completed-orders', [DashboardController::class, 'retailCompletedOrders'])->name('retail-completed-orders');
        Route::get('/tl-dashboard-pdf', [DashboardController::class, 'exportTlDashboardPdf'])->name('tl-dashboard-pdf');
        Route::get('/dashboard/export-tl-dashboard-excel', [DashboardController::class, 'exportTlDashboardExcel'])->name('dashboard.export-tl-dashboard-excel');
        Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

        // Users

        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::post('/store', [UserController::class, 'store'])->name('users.store');

        // Role

        Route::get('/roles', [RoleController::class, 'index']);
        Route::post('/roles/store', [RoleController::class, 'store'])->name('roles.store');
        Route::get('roles/{roleId}/give-permission', [RoleController::class, 'addPermissionToRole'])->name('roles.addPermissionToRole');
        Route::put('roles/{roleId}/give-permission', [RoleController::class, 'givePermissionToRole'])->name('roles.givePermissionToRole');


        // Category

        Route::get('/categories', [CategoryController::class, 'index']);
        Route::post('/categories/store', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/edit/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::post('/categories/update/{id}', [CategoryController::class, 'update'])->name('categories.update');
        Route::get('/categories/delete/{id}', [CategoryController::class, 'delete'])->name('categories.delete');

        // Customers

        Route::get('/customers', [CustomerController::class, 'index']);
        Route::post('/customers/store', [CustomerController::class, 'store'])->name('customers.store');
        Route::get('/get-customer-details/{customer_id}', [CustomerController::class, 'getCustomerDetails']);
        Route::get('/customers/edit/{id}', [CustomerController::class, 'edit'])->name('customers.edit');
        Route::post('/customers/update/{id}', [CustomerController::class, 'update'])->name('customers.update');
        Route::get('/customers/delete/{id}', [CustomerController::class, 'delete'])->name('customers.delete');

        // Employees

        Route::get('/employees', [EmployeeController::class, 'index']);
        Route::post('/employees/store', [EmployeeController::class, 'store'])->name('employees.store');
        Route::get('/employees/edit/{id}', [EmployeeController::class, 'edit'])->name('employees.edit');
        Route::post('/employees/update/{id}', [EmployeeController::class, 'update'])->name('employees.update');
        Route::get('/employees/delete/{id}', [EmployeeController::class, 'delete'])->name('employees.delete');

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
        Route::post('/productDispatch', [ProductController::class, 'productDispatch']);
        Route::post('/productDispatchForHitech', [ProductController::class, 'productDispatchForHitech']);
        Route::post('/allocateEmployee', [ProductionController::class, 'allocateEmployee'])->name('production.allocateEmployee');
        Route::post('/allocateDispatchEmployee', [ProductionController::class, 'allocateDispatchEmployee'])->name('production.allocateDispatchEmployee');
        Route::get('/invoice-request/{quotation_id}', [ProductionController::class, 'invoiceRequest']);
        Route::post('/invoice-request-submit', [ProductionController::class, 'invoiceRequestSubmit'])->name('invoice.request');
        Route::get('/invoice-request-details', [ProductionController::class, 'invoiceRequestDetails']);
        Route::post('/invoice_status_completed', [ProductionController::class, 'invoiceStatusUpdate']);
        Route::get('/invoice-request-information/{request_id}', [ProductionController::class, 'invoiceRequestinformation']);
        Route::get('/active-order-details', [ProductionController::class, 'activeOrderDetails'])->name('active-order-details');
        Route::get('/barcode-print/{productId}', [ProductionController::class, 'barcodePrint']);
        Route::get('/getQuotationDetails/{quotationid}', [ProductionController::class, 'getQuotationDetails']);
        Route::post('/partial_dispatch', [ProductionController::class, 'partialDispatch'])->name('partialdispatch');
        Route::post('/paymentReportFilter', [PaymentController::class, 'paymentReportFilter'])->name('paymentReportFilter');
        Route::get('/product-stock-report', [ReportController::class, 'productStockReport']);
        Route::get('/component-stock-report', [ReportController::class, 'componentStockReport']);
        Route::get('/sales-stock-report', [ReportController::class, 'salesStockReport']);
        Route::get('/quotation-report', [ReportController::class, 'quotationReport']);

        Route::get('/permissions', [PermissionController::class, 'index']);
        Route::post('/permissions/store', [PermissionController::class, 'store'])->name('permission.store');
        Route::get('/getBomDetails/{productId}/{dataType}', [ProductionController::class, 'getBomDetails']);

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

        // Reports

        Route::get('/collection-report', [ReportController::class, 'collectionReport']);
        Route::get('/employee-wise-production-report', [ReportController::class, 'employeeWiseProductionReport']);
        Route::post('/employeeReportFilter', [ReportController::class, 'employeeReportFilter'])->name('employeeFilter');
        Route::get('/task-report', [ReportController::class, 'taskReport']);
        Route::post('/task-report-filter', [ReportController::class, 'taskReportFilter'])->name('taskReportFilter');
        Route::post('/quotation-report-filter', [ReportController::class, 'quotationReportFilter'])->name('quotationReportFilter');

        Route::post('/sales-stock-report-filter', [ReportController::class, 'salesStockReportFilter'])->name('salesStockReportFilter');

        Route::post('/quotation/capture-photo', [QuotationController::class, 'capturePhoto'])
            ->name('quotation.capture.photo');
        Route::post('/collection-report-filter', [ReportController::class, 'collectionFilter'])->name('collectionReportFilter');
        Route::post('/stock-report-filter', [ReportController::class, 'prostockReportFilter'])->name('prostockReportFilter');
        Route::post('/component-report-filter', [ReportController::class, 'componentstockReportFilter'])->name('componentstockReportFilter');

        // Route::view('/quotations', 'pages.quotations.index');
        // Route::view('/manage-bom', 'pages.bom.index');
        Route::view('/reports', 'pages.reports.index');
        Route::post('/payment-collection-report', [PaymentController::class, 'paymentReportFilter'])->name('paymentcollectReportFilter');

        Route::post('/orderstatus-report-filter', [DashboardController::class, 'orderstatusReportFilter'])
            ->name('orderstatusReportFilter');
        Route::post('/quotestatus/filter', [DashboardController::class, 'Quotefilter'])->name('QuotestatusReportFilter');
        Route::get('/collection-report-export-excel', [ReportController::class, 'exportCollectionReportExcel'])
            ->name('collection.report.export.excel');

        Route::get('/collection-report-export-pdf', [ReportController::class, 'exportCollectionReportPdf'])
            ->name('collection.report.export.pdf');

        Route::get('/quotation-report-export-excel', [ReportController::class, 'exportQuotationReportExcel'])
            ->name('quotation.report.export.excel');

        Route::get('/quotation-report-export-pdf', [ReportController::class, 'exportQuotationReportPdf'])
            ->name('quotation.report.export.pdf');

        Route::get('/product-stock-report-export-excel', [ReportController::class, 'exportProductStockReportExcel'])
            ->name('product.stock.report.export.excel');

        Route::get('/product-stock-report-export-pdf', [ReportController::class, 'exportProductStockReportPdf'])
            ->name('product.stock.report.export.pdf');

        Route::get('/component-stock-report-export-excel', [ReportController::class, 'exportComponentStockReportExcel'])
            ->name('component.stock.report.export.excel');

        Route::get('/component-stock-report-export-pdf', [ReportController::class, 'exportComponentStockReportPdf'])
            ->name('component.stock.report.export.pdf');

        Route::get('/employee-wise-production-report-export-excel', [ReportController::class, 'exportEmployeeWiseProductionReportExcel'])
            ->name('employee.wise.production.report.export.excel');

        Route::get('/employee-wise-production-report-export-pdf', [ReportController::class, 'exportEmployeeWiseProductionReportPdf'])
            ->name('employee.wise.production.report.export.pdf');

        Route::get('/quotation-products-report-export-excel', [ReportController::class, 'exportQuotationProductsReportExcel'])
            ->name('quotation.products.report.export.excel');

        Route::get('/quotation-products-report-export-pdf', [ReportController::class, 'exportQuotationProductsReportPdf'])
            ->name('quotation.products.report.export.pdf');

        Route::get('/sales-stock-report-export-excel', [ReportController::class, 'exportSalesStockReportExcel'])
            ->name('sales.stock.report.export.excel');

        Route::get('/sales-stock-report-export-pdf', [ReportController::class, 'exportSalesStockReportPdf'])
            ->name('sales.stock.report.export.pdf');

        Route::get('/task-report-export-excel', [ReportController::class, 'exportTaskReportExcel'])
            ->name('task.report.export.excel');

        Route::get('/task-report-export-pdf', [ReportController::class, 'exportTaskReportPdf'])
            ->name('task.report.export.pdf');
    }
);
