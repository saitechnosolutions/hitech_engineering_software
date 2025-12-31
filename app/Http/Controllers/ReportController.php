<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\QuotationProducts;
use Illuminate\Http\Request as HttpRequest;
use App\DataTables\TaskReportDataTable;
use App\DataTables\CollectionReportDataTable;
use App\DataTables\ProductStockReportDataTable;
use App\Http\Resources\EmployeeReportCollection;
use App\DataTables\ComponentStockReportDataTable;
use App\DataTables\EmployeeWiseProductionReportDataTable;
use App\DataTables\QuotationReportDataTable;
use App\DataTables\SalesStockReportDataTable;
use App\Models\Product;
use App\Models\ProductComponents;
use App\Models\Quotation;
use App\Exports\CollectionReportExport;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function collectionReport(CollectionReportDataTable $dataTable)
    {
        return $dataTable->render('pages.reports.collection-report');
    }

    public function employeeWiseProductionReport(EmployeeWiseProductionReportDataTable $dataTable)
    {
        return $dataTable->render('pages.reports.employee-wise-production-report');
    }

    public function employeeReportFilter(Request $request)
    {

        $employeeIds = $request->employeeIds;
        $quotationIds = $request->quotationIds;
        $teamName = $request->teamName;
        $fromdate = $request->fromdate;
        $todate = $request->todate;

        $quotationProducts = QuotationProducts::with('quotation', 'msFabricationEmployee', 'ssFabricationEmployee', 'product');

        if (!empty($teamName)) {
            if ($teamName == 'ss_fabrication_team') {
                if (!empty($employeeIds)) {
                    $quotationProducts->where('ss_fabrication_emp_id', $employeeIds);
                }
            } else {
                if (!empty($employeeIds)) {
                    $quotationProducts->where('ms_fabrication_emp_id', $employeeIds);
                }
            }
        }

        if (!empty($quotationIds)) {
            $quotationProducts->where('quotation_id', $quotationIds);
        }

        if (!empty($fromdate) && !empty($todate)) {
            $quotationProducts->whereHas('quotation', function ($q) use ($fromdate, $todate) {
                $q->whereBetween('quotation_date', [$fromdate, $todate]);
            });
        }

        $quotationProducts = $quotationProducts->get();

        return response()->json([
            "status" => 'success',
            "message" => 'Employee Report Generated',
            "data" => new EmployeeReportCollection($quotationProducts)
        ]);
    }

    public function taskReport(TaskReportDataTable $dataTable)
    {
        return $dataTable->render('pages.reports.task-report');
    }

    public function taskReportFilter(Request $request)
    {
        $employeeIds = $request->employeeIds;
        $status = $request->status;
        $fromdate = $request->fromdate;
        $todate = $request->todate;

        $tasks = Task::with('assignedTo');

        // Status filter
        if (!empty($status)) {
            $tasks->where('status', $status);
        }

        // Employee filter
        if (!empty($employeeIds)) {
            $tasks->where('assigned_to', $employeeIds);
        }

        // Task date filter
        if (!empty($fromdate) && !empty($todate)) {
            $tasks->whereBetween('task_date', [$fromdate, $todate]);
        }

        $tasks = $tasks->get();

        return response()->json([
            "status" => 'success',
            "message" => 'Task Report Generated',
            "data" => $tasks
        ]);
    }


    public function prostockReportFilter(Request $request)
    {
        $productId = $request->productIds; // single product
        $stocks    = $request->stocks;

        $query = Product::query();


        if (!empty($productId)) {
            $query->where('id', $productId);
        } elseif (!empty($stocks)) {
            if ($stocks === 'low') {
                $query->where('stock_qty', '<=', 10);
            } elseif ($stocks === 'high') {
                $query->where('stock_qty', '>', 10);
            }
        }


        $products = $query->orderBy('product_name')->get();


        $data = $products->map(function ($product) {
            return [
                'product_name' => $product->product_name,
                'part_number'  => $product->part_number,
                'variation'    => $product->variation ?? '-', // important
                'color'        => $product->color ?? '-',
                'material'     => $product->material ?? '-',
                'stock_qty'    => $product->stock_qty,
            ];
        });

        return response()->json([
            'status'  => 'success',
            'message' => 'Product Stock Report Generated',
            'data'    => $data
        ]);
    }
    public function componentstockReportFilter(Request $request)
    {
        $productId = $request->productIds; // single product
        $stocks    = $request->stocks;

        $query = ProductComponents::query();


        if (!empty($productId)) {
            $query->where('id', $productId);
        } elseif (!empty($stocks)) {
            if ($stocks === 'low') {
                $query->where('stock_qty', '<=', 50);
            } elseif ($stocks === 'high') {
                $query->where('stock_qty', '>', 50);
            }
        }


        $products = $query->orderBy('component_name')->get();


        $data = $products->map(function ($product) {
            return [
                'component_name' => $product->component_name,
                'price'  => $product->unit_price,
                'unit_price'  => $product->unit_price,
                'stock_qty'    => $product->stock_qty,
            ];
        });

        return response()->json([
            'status'  => 'success',
            'message' => 'Component  Stock Report Generated',
            'data'    => $data
        ]);
    }

    public function salesStockReportFilter(Request $request)
    {
        $quotation_id = $request->quotationIds;
        $fromdate = $request->fromdate;
        $todate = $request->todate;

        $query = Quotation::with(['customer']);

        // Quotation filter
        if (!empty($quotation_id)) {
            $query->where('id', $quotation_id);
        }

        // Date range
        if (!empty($fromdate) && !empty($todate)) {
            $query->whereBetween('quotation_date', [$fromdate, $todate]);
        }

        $results = $query->get();

        $data = $results->map(function ($item) {
            $batch = \App\Models\QuotationBatch::whereJsonContains('quotation_ids', $item->id)->first();

            return [
                'id' => $item->id,
                'quotation_no' => $item->quotation_no,
                'quotation_date' => formatDate($item->quotation_date),
                'customer' => $item->customer?->customer_name,
                'batch_date' => $batch ? formatDate($batch->batch_date) : null,
                'total_collectable_amount' => $item->total_collectable_amount,
            ];
        });

        return response()->json([
            "status" => 'success',
            "message" => 'Sales Stock Report Generated',
            "data" => $data
        ]);
    }




    public function collectionFilter(Request $request)
    {
        $quotation_id = $request->quotationIds;
        $fromdate = $request->fromdate;
        $todate = $request->todate;
        $customer_name = $request->company_name;
        $rm = $request->rm;
        $batch = $request->batch;

        $query = Quotation::with(['customer.employee', 'payments']);

        // Quotation filter
        if (!empty($quotation_id)) {
            $query->where('id', $quotation_id);
        }

        // Company name filter (customer relation)
        if (!empty($customer_name)) {
            $query->whereHas('customer', function ($q) use ($customer_name) {
                $q->where('customer_name', 'like', "%$customer_name%");
            });
        }

        // RM filter (customer->employee relation)
        if (!empty($rm)) {
            $query->whereHas('customer.employee', function ($q) use ($rm) {
                $q->where('name', 'like', "%$rm%");
            });
        }

        // Batch filter (QuotationBatch relation via quotation_ids JSON, MySQL compatible)
        if (!empty($batch)) {
            $batches = \App\Models\QuotationBatch::whereDate('batch_date', $batch)->get();
            $quotationIds = [];
            foreach ($batches as $b) {
                $quotationIds = array_merge($quotationIds, $b->quotation_ids ?? []);
            }
            if (!empty($quotationIds)) {
                $query->whereIn('id', $quotationIds);
            } else {
                // No matching quotations for this batch date, force empty result
                $query->whereRaw('0=1');
            }
        }

        // Quotation date filter
        if (!empty($fromdate) && !empty($todate)) {
            $query->whereBetween('quotation_date', [$fromdate, $todate]);
        }

        $results = $query->get();

        $data = $results->map(function ($item) {
            // Find batch by quotation id
            $batch = \App\Models\QuotationBatch::whereJsonContains('quotation_ids', $item->id)->first();
            return [
                'id' => $item->id,
                'customer_name' => $item->customer?->customer_name,
                'batch' => $batch ? formatDate($batch->batch_date) : null,
                'quotation_no' => $item->quotation_no,
                'quotation_date' => formatDate($item->quotation_date),
                'rm' => $item->customer?->employee?->name,
                'total_collectable_amount' => $item->total_collectable_amount,
                'received_amount' => number_format($item->payments->sum('amount'), 2),
                'pending_amount' => number_format($item->total_collectable_amount - $item->payments->sum('amount'), 2),
            ];
        });

        return response()->json([
            "status" => 'success',
            "message" => 'Collection Report Generated',
            "data" => $data
        ]);
    }
    public function quotationReportFilter(Request $request)
    {
        $quotation_id   = $request->quotationIds;
        $fromdate       = $request->fromdate;
        $todate         = $request->todate;
        $customer_name  = $request->company_name;
        $rm             = $request->rm;
        $batch          = $request->batch;
        $status         = $request->status;

        // NEW
        $dispatch_status = $request->dispatch_status;
        $dispatch_image  = $request->dispatch_image;

        $query = Quotation::with(['customer.employee', 'payments']);

        // Quotation filter
        if (!empty($quotation_id)) {
            $query->where('id', $quotation_id);
        }

        // Company name filter
        if (!empty($customer_name)) {
            $query->whereHas('customer', function ($q) use ($customer_name) {
                $q->where('customer_name', 'like', "%$customer_name%");
            });
        }

        // Payment status
        if (!empty($status)) {
            $query->where('payment_status', $status);
        }

        // 🔹 Dispatch / Production Status filter
        if (!empty($dispatch_status)) {
            $query->where('production_status', $dispatch_status);
        }

        // 🔹 Dispatch Image filter
        if (!empty($dispatch_image)) {
            if ($dispatch_image === 'with_image') {
                $query->whereNotNull('dispatch_image');
            }

            if ($dispatch_image === 'without_image') {
                $query->whereNull('dispatch_image');
            }
        }

        // RM filter
        if (!empty($rm)) {
            $query->whereHas('customer.employee', function ($q) use ($rm) {
                $q->where('name', 'like', "%$rm%");
            });
        }

        // Batch filter
        if (!empty($batch)) {
            $batches = \App\Models\QuotationBatch::whereDate('batch_date', $batch)->get();
            $quotationIds = [];

            foreach ($batches as $b) {
                $quotationIds = array_merge($quotationIds, $b->quotation_ids ?? []);
            }

            if (!empty($quotationIds)) {
                $query->whereIn('id', $quotationIds);
            } else {
                $query->whereRaw('0=1');
            }
        }

        // Date range
        if (!empty($fromdate) && !empty($todate)) {
            $query->whereBetween('quotation_date', [$fromdate, $todate]);
        }

        $results = $query->get();

        $data = $results->map(function ($item) {
            $batch = \App\Models\QuotationBatch::whereJsonContains('quotation_ids', $item->id)->first();

            return [
                'id' => $item->id,
                'customer_name' => $item->customer?->customer_name,
                'batch' => $batch ? formatDate($batch->batch_date) : null,
                'quotation_no' => $item->quotation_no,
                'quotation_date' => formatDate($item->quotation_date),
                'rm' => $item->customer?->employee?->name,
                'dispatch_team_id' => $item->dispatch_team_id,
                'production_status' => $item->production_status,
                'dispatch_image' => $item->dispatch_image,
                'total_collectable_amount' => $item->total_collectable_amount,
                'received_amount' => number_format($item->payments->sum('amount'), 2),
                'pending_amount' => number_format(
                    $item->total_collectable_amount - $item->payments->sum('amount'),
                    2
                ),
            ];
        });

        return response()->json([
            "status"  => 'success',
            "message" => 'Collection Report Generated',
            "data"    => $data
        ]);
    }


    public function productStockReport(ProductStockReportDataTable $dataTable)
    {
        return $dataTable->render('pages.reports.product-stock-report');
    }

    public function componentStockReport(ComponentStockReportDataTable $dataTable)
    {
        return $dataTable->render('pages.reports.component-stock-report');
    }

    public function salesStockReport(SalesStockReportDataTable $dataTable)
    {
        return $dataTable->render('pages.reports.sales-stock-report');
    }

    public function quotationReport(QuotationReportDataTable $dataTable)
    {
        return $dataTable->render('pages.reports.quotation-report');
    }

    public function exportCollectionReportExcel(Request $request)
    {
        $quotation_id = $request->quotationIds;
        $fromdate = $request->fromdate;
        $todate = $request->todate;
        $customer_name = $request->company_name;
        $rm = $request->rm;
        $batch = $request->batch;

        $query = Quotation::with(['customer.employee', 'payments']);

        // Quotation filter
        if (!empty($quotation_id)) {
            $query->where('id', $quotation_id);
        }

        // Company name filter (customer relation)
        if (!empty($customer_name)) {
            $query->whereHas('customer', function ($q) use ($customer_name) {
                $q->where('customer_name', 'like', "%$customer_name%");
            });
        }

        // RM filter (customer->employee relation)
        if (!empty($rm)) {
            $query->whereHas('customer.employee', function ($q) use ($rm) {
                $q->where('name', 'like', "%$rm%");
            });
        }

        // Batch filter (QuotationBatch relation via quotation_ids JSON, MySQL compatible)
        if (!empty($batch)) {
            $batches = \App\Models\QuotationBatch::whereDate('batch_date', $batch)->get();
            $quotationIds = [];
            foreach ($batches as $b) {
                $quotationIds = array_merge($quotationIds, $b->quotation_ids ?? []);
            }
            if (!empty($quotationIds)) {
                $query->whereIn('id', $quotationIds);
            } else {
                // No matching quotations for this batch date, force empty result
                $query->whereRaw('0=1');
            }
        }

        // Quotation date filter
        if (!empty($fromdate) && !empty($todate)) {
            $query->whereBetween('quotation_date', [$fromdate, $todate]);
        }

        $results = $query->get();

        $data = $results->map(function ($item) {
            // Find batch by quotation id
            $batch = \App\Models\QuotationBatch::whereJsonContains('quotation_ids', $item->id)->first();
            return [
                'id' => $item->id,
                'customer_name' => $item->customer?->customer_name,
                'batch' => $batch ? formatDate($batch->batch_date) : null,
                'quotation_no' => $item->quotation_no,
                'quotation_date' => formatDate($item->quotation_date),
                'rm' => $item->customer?->employee?->name,
                'total_collectable_amount' => $item->total_collectable_amount,
                'received_amount' => number_format($item->payments->sum('amount'), 2),
                'pending_amount' => number_format($item->total_collectable_amount - $item->payments->sum('amount'), 2),
            ];
        });

        $writer = SimpleExcelWriter::create(storage_path('app/public/collection_report_' . date('Y-m-d_H-i-s') . '.xlsx'));

        // Add headers
        $writer->addRow([
            'S.No',
            'Company Name',
            'Batch',
            'Quotation No',
            'Quotation Date',
            'RM',
            'Quotation Amount',
            'Received Amount',
            'Pending Amount',
        ]);

        // Add data rows
        foreach ($data as $index => $row) {
            $writer->addRow([
                $index + 1,
                $row['customer_name'],
                $row['batch'],
                $row['quotation_no'],
                $row['quotation_date'],
                $row['rm'],
                $row['total_collectable_amount'],
                $row['received_amount'],
                $row['pending_amount'],
            ]);
        }

        $writer->close();

        return response()->download(storage_path('app/public/collection_report_' . date('Y-m-d_H-i-s') . '.xlsx'))->deleteFileAfterSend();
    }

    public function exportCollectionReportPdf(Request $request)
    {
        $quotation_id = $request->quotationIds;
        $fromdate = $request->fromdate;
        $todate = $request->todate;
        $customer_name = $request->company_name;
        $rm = $request->rm;
        $batch = $request->batch;

        $query = Quotation::with(['customer.employee', 'payments']);

        // Quotation filter
        if (!empty($quotation_id)) {
            $query->where('id', $quotation_id);
        }

        // Company name filter (customer relation)
        if (!empty($customer_name)) {
            $query->whereHas('customer', function ($q) use ($customer_name) {
                $q->where('customer_name', 'like', "%$customer_name%");
            });
        }

        // RM filter (customer->employee relation)
        if (!empty($rm)) {
            $query->whereHas('customer.employee', function ($q) use ($rm) {
                $q->where('name', 'like', "%$rm%");
            });
        }

        // Batch filter (QuotationBatch relation via quotation_ids JSON, MySQL compatible)
        if (!empty($batch)) {
            $batches = \App\Models\QuotationBatch::whereDate('batch_date', $batch)->get();
            $quotationIds = [];
            foreach ($batches as $b) {
                $quotationIds = array_merge($quotationIds, $b->quotation_ids ?? []);
            }
            if (!empty($quotationIds)) {
                $query->whereIn('id', $quotationIds);
            } else {
                // No matching quotations for this batch date, force empty result
                $query->whereRaw('0=1');
            }
        }

        // Quotation date filter
        if (!empty($fromdate) && !empty($todate)) {
            $query->whereBetween('quotation_date', [$fromdate, $todate]);
        }

        $results = $query->get();

        $title = 'Collection Report - ' . date('d-m-Y H:i:s');

        $pdf = Pdf::loadView('pages.reports.exports.collection_report_pdf', compact('results', 'title'));

        return $pdf->stream('collection_report_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    public function exportQuotationReportExcel(Request $request)
    {
        $quotation_id   = $request->quotationIds;
        $fromdate       = $request->fromdate;
        $todate         = $request->todate;
        $customer_name  = $request->company_name;
        $rm             = $request->rm;
        $batch          = $request->batch;
        $status         = $request->status;
        $dispatch_status = $request->dispatch_status;
        $dispatch_image  = $request->dispatch_image;

        $query = Quotation::with(['customer.employee', 'payments']);

        // Quotation filter
        if (!empty($quotation_id)) {
            $query->where('id', $quotation_id);
        }

        // Company name filter
        if (!empty($customer_name)) {
            $query->whereHas('customer', function ($q) use ($customer_name) {
                $q->where('customer_name', 'like', "%$customer_name%");
            });
        }

        // Payment status
        if (!empty($status)) {
            $query->where('payment_status', $status);
        }

        // Dispatch / Production Status filter
        if (!empty($dispatch_status)) {
            $query->where('production_status', $dispatch_status);
        }

        // Dispatch Image filter
        if (!empty($dispatch_image)) {
            if ($dispatch_image === 'with_image') {
                $query->whereNotNull('dispatch_image');
            }

            if ($dispatch_image === 'without_image') {
                $query->whereNull('dispatch_image');
            }
        }

        // RM filter
        if (!empty($rm)) {
            $query->whereHas('customer.employee', function ($q) use ($rm) {
                $q->where('name', 'like', "%$rm%");
            });
        }

        // Batch filter
        if (!empty($batch)) {
            $batches = \App\Models\QuotationBatch::whereDate('batch_date', $batch)->get();
            $quotationIds = [];

            foreach ($batches as $b) {
                $quotationIds = array_merge($quotationIds, $b->quotation_ids ?? []);
            }

            if (!empty($quotationIds)) {
                $query->whereIn('id', $quotationIds);
            } else {
                $query->whereRaw('0=1');
            }
        }

        // Date range
        if (!empty($fromdate) && !empty($todate)) {
            $query->whereBetween('quotation_date', [$fromdate, $todate]);
        }

        $results = $query->get();

        $data = $results->map(function ($item) {
            $batch = \App\Models\QuotationBatch::whereJsonContains('quotation_ids', $item->id)->first();

            return [
                'id' => $item->id,
                'customer_name' => $item->customer?->customer_name,
                'batch' => $batch ? formatDate($batch->batch_date) : null,
                'quotation_no' => $item->quotation_no,
                'quotation_date' => formatDate($item->quotation_date),
                'rm' => $item->customer?->employee?->name,
                'dispatch_team_id' => $item->dispatch_team_id,
                'production_status' => $item->production_status,
                'dispatch_image' => $item->dispatch_image,
                'total_collectable_amount' => $item->total_collectable_amount,
                'received_amount' => number_format($item->payments->sum('amount'), 2),
                'pending_amount' => number_format(
                    $item->total_collectable_amount - $item->payments->sum('amount'),
                    2
                ),
            ];
        });

        $writer = SimpleExcelWriter::create(storage_path('app/public/quotation_report_' . date('Y-m-d_H-i-s') . '.xlsx'));

        // Add headers
        $writer->addRow([
            'S.No',
            'Company Name',
            'Batch',
            'Quotation No',
            'Quotation Date',
            'RM',
            'Dispatch Team',
            'Production Status',
            'Dispatch Image',
            'Quotation Amount',
            'Received Amount',
            'Pending Amount',
        ]);

        // Add data rows
        foreach ($data as $index => $row) {
            $writer->addRow([
                $index + 1,
                $row['customer_name'],
                $row['batch'],
                $row['quotation_no'],
                $row['quotation_date'],
                $row['rm'],
                $row['dispatch_team_id'],
                $row['production_status'],
                $row['dispatch_image'],
                $row['total_collectable_amount'],
                $row['received_amount'],
                $row['pending_amount'],
            ]);
        }

        $writer->close();

        return response()->download(storage_path('app/public/quotation_report_' . date('Y-m-d_H-i-s') . '.xlsx'))->deleteFileAfterSend();
    }

    public function exportQuotationReportPdf(Request $request)
    {
        $quotation_id   = $request->quotationIds;
        $fromdate       = $request->fromdate;
        $todate         = $request->todate;
        $customer_name  = $request->company_name;
        $rm             = $request->rm;
        $batch          = $request->batch;
        $status         = $request->status;
        $dispatch_status = $request->dispatch_status;
        $dispatch_image  = $request->dispatch_image;

        $query = Quotation::with(['customer.employee', 'payments']);

        // Quotation filter
        if (!empty($quotation_id)) {
            $query->where('id', $quotation_id);
        }

        // Company name filter
        if (!empty($customer_name)) {
            $query->whereHas('customer', function ($q) use ($customer_name) {
                $q->where('customer_name', 'like', "%$customer_name%");
            });
        }

        // Payment status
        if (!empty($status)) {
            $query->where('payment_status', $status);
        }

        // Dispatch / Production Status filter
        if (!empty($dispatch_status)) {
            $query->where('production_status', $dispatch_status);
        }

        // Dispatch Image filter
        if (!empty($dispatch_image)) {
            if ($dispatch_image === 'with_image') {
                $query->whereNotNull('dispatch_image');
            }

            if ($dispatch_image === 'without_image') {
                $query->whereNull('dispatch_image');
            }
        }

        // RM filter
        if (!empty($rm)) {
            $query->whereHas('customer.employee', function ($q) use ($rm) {
                $q->where('name', 'like', "%$rm%");
            });
        }

        // Batch filter
        if (!empty($batch)) {
            $batches = \App\Models\QuotationBatch::whereDate('batch_date', $batch)->get();
            $quotationIds = [];

            foreach ($batches as $b) {
                $quotationIds = array_merge($quotationIds, $b->quotation_ids ?? []);
            }

            if (!empty($quotationIds)) {
                $query->whereIn('id', $quotationIds);
            } else {
                $query->whereRaw('0=1');
            }
        }

        // Date range
        if (!empty($fromdate) && !empty($todate)) {
            $query->whereBetween('quotation_date', [$fromdate, $todate]);
        }

        $results = $query->get();

        $title = 'Quotation Report - ' . date('d-m-Y H:i:s');

        $pdf = Pdf::loadView('pages.reports.exports.quotation_report_pdf', compact('results', 'title'));

        return $pdf->stream('quotation_report_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    public function exportProductStockReportExcel(Request $request)
    {
        $productId = $request->productIds;
        $stocks = $request->stocks;

        $query = Product::query();

        if (!empty($productId)) {
            $query->where('id', $productId);
        } elseif (!empty($stocks)) {
            if ($stocks === 'low') {
                $query->where('stock_qty', '<=', 10);
            } elseif ($stocks === 'high') {
                $query->where('stock_qty', '>', 10);
            }
        }

        $products = $query->orderBy('product_name')->get();

        $data = $products->map(function ($product) {
            return [
                'product_name' => $product->product_name,
                'part_number' => $product->part_number,
                'variation' => $product->variation ?? '-',
                'color' => $product->color ?? '-',
                'material' => $product->material ?? '-',
                'stock_qty' => $product->stock_qty,
            ];
        });

        $writer = SimpleExcelWriter::create(storage_path('app/public/product_stock_report_' . date('Y-m-d_H-i-s') . '.xlsx'));

        // Add headers
        $writer->addRow([
            'S.No',
            'Product Name',
            'Part Number',
            'Variation',
            'Color',
            'Material',
            'Available Quantity',
        ]);

        // Add data rows
        foreach ($data as $index => $row) {
            $writer->addRow([
                $index + 1,
                $row['product_name'],
                $row['part_number'],
                $row['variation'],
                $row['color'],
                $row['material'],
                $row['stock_qty'],
            ]);
        }

        $writer->close();

        return response()->download(storage_path('app/public/product_stock_report_' . date('Y-m-d_H-i-s') . '.xlsx'))->deleteFileAfterSend();
    }

    public function exportProductStockReportPdf(Request $request)
    {
        $productId = $request->productIds;
        $stocks = $request->stocks;

        $query = Product::query();

        if (!empty($productId)) {
            $query->where('id', $productId);
        } elseif (!empty($stocks)) {
            if ($stocks === 'low') {
                $query->where('stock_qty', '<=', 10);
            } elseif ($stocks === 'high') {
                $query->where('stock_qty', '>', 10);
            }
        }

        $products = $query->orderBy('product_name')->get();

        $title = 'Product Stock Report - ' . date('d-m-Y H:i:s');

        $pdf = Pdf::loadView('pages.reports.exports.product_stock_report_pdf', compact('products', 'title'));

        return $pdf->stream('product_stock_report_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    public function exportComponentStockReportExcel(Request $request)
    {
        $productId = $request->productIds;
        $stocks = $request->stocks;

        $query = ProductComponents::query();

        if (!empty($productId)) {
            $query->where('id', $productId);
        } elseif (!empty($stocks)) {
            if ($stocks === 'low') {
                $query->where('stock_qty', '<=', 50);
            } elseif ($stocks === 'high') {
                $query->where('stock_qty', '>', 50);
            }
        }

        $components = $query->orderBy('component_name')->get();

        $data = $components->map(function ($component) {
            return [
                'component_name' => $component->component_name,
                'price' => $component->price,
                'unit_price' => $component->unit_price,
                'stock_qty' => $component->stock_qty,
            ];
        });

        $writer = SimpleExcelWriter::create(storage_path('app/public/component_stock_report_' . date('Y-m-d_H-i-s') . '.xlsx'));

        // Add headers
        $writer->addRow([
            'S.No',
            'Component Name',
            'Price',
            'Unit Price',
            'Available Quantity',
        ]);

        // Add data rows
        foreach ($data as $index => $row) {
            $writer->addRow([
                $index + 1,
                $row['component_name'],
                $row['price'],
                $row['unit_price'],
                $row['stock_qty'],
            ]);
        }

        $writer->close();

        return response()->download(storage_path('app/public/component_stock_report_' . date('Y-m-d_H-i-s') . '.xlsx'))->deleteFileAfterSend();
    }

    public function exportComponentStockReportPdf(Request $request)
    {
        $productId = $request->productIds;
        $stocks = $request->stocks;

        $query = ProductComponents::query();

        if (!empty($productId)) {
            $query->where('id', $productId);
        } elseif (!empty($stocks)) {
            if ($stocks === 'low') {
                $query->where('stock_qty', '<=', 50);
            } elseif ($stocks === 'high') {
                $query->where('stock_qty', '>', 50);
            }
        }

        $components = $query->orderBy('component_name')->get();

        $title = 'Component Stock Report - ' . date('d-m-Y H:i:s');

        $pdf = Pdf::loadView('pages.reports.exports.component_stock_report_pdf', compact('components', 'title'));

        return $pdf->stream('component_stock_report_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    public function exportEmployeeWiseProductionReportExcel(Request $request)
    {
        $employeeId = $request->employeeIds;
        $quotationId = $request->quotationIds;
        $teamName = $request->teamName;
        $fromdate = $request->fromdate;
        $todate = $request->todate;


        $query = QuotationProducts::with(['msFabricationEmployee', 'quotation', 'quotation']);

        if (!empty($employeeId)) {
            $query->where('employee_id', $employeeId);
        }

        if (!empty($quotationId)) {
            $query->where('quotation_id', $quotationId);
        }

        if (!empty($teamName)) {
            $query->where('team_name', $teamName);
        }

        if (!empty($fromdate) && !empty($todate)) {
            $query->whereBetween('created_at', [$fromdate, $todate]);
        }

        $productions = $query->orderBy('created_at', 'desc')->get();

        $data = $productions->map(function ($production) {
            return [
                'employee_name' => $production->employee?->name ?? '-',
                'quotation_no' => $production->quotation?->quotation_no ?? '-',
                'product_name' => $production->quotationProduct?->product_name ?? '-',
                'team_name' => $production->team_name ?? '-',
                'product_qty' => $production->product_qty ?? 0,
            ];
        });

        $writer = SimpleExcelWriter::create(storage_path('app/public/employee_wise_production_report_' . date('Y-m-d_H-i-s') . '.xlsx'));

        // Add headers
        $writer->addRow([
            'S.No',
            'Employee Name',
            'Quotation No',
            'Product Name',
            'Team Name',
            'Product Quantity',
        ]);

        // Add data rows
        foreach ($data as $index => $row) {
            $writer->addRow([
                $index + 1,
                $row['employee_name'],
                $row['quotation_no'],
                $row['product_name'],
                $row['team_name'],
                $row['product_qty'],
            ]);
        }

        $writer->close();

        return response()->download(storage_path('app/public/employee_wise_production_report_' . date('Y-m-d_H-i-s') . '.xlsx'))->deleteFileAfterSend();
    }

    public function exportEmployeeWiseProductionReportPdf(Request $request)
    {
        $employeeId = $request->employeeIds;
        $quotationId = $request->quotationIds;
        $teamName = $request->teamName;
        $fromdate = $request->fromdate;
        $todate = $request->todate;

        $query = QuotationProducts::with(['msFabricationEmployee', 'quotation', 'quotation']);

        if (!empty($employeeId)) {
            $query->where('employee_id', $employeeId);
        }

        if (!empty($quotationId)) {
            $query->where('quotation_id', $quotationId);
        }

        if (!empty($teamName)) {
            $query->where('team_name', $teamName);
        }

        if (!empty($fromdate) && !empty($todate)) {
            $query->whereBetween('created_at', [$fromdate, $todate]);
        }

        $productions = $query->orderBy('created_at', 'desc')->get();

        $title = 'Employee Wise Production Report - ' . date('d-m-Y H:i:s');

        $pdf = Pdf::loadView('pages.reports.exports.employee_wise_production_report_pdf', compact('productions', 'title'));

        return $pdf->stream('employee_wise_production_report_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    public function exportQuotationProductsReportExcel(Request $request)
    {
        $employeeId = $request->employeeIds;
        $quotationId = $request->quotationIds;
        $teamName = $request->teamName;
        $fromdate = $request->fromdate;
        $todate = $request->todate;

        $query = QuotationProducts::with('quotation', 'msFabricationEmployee', 'ssFabricationEmployee', 'product');

        if (!empty($teamName)) {
            if ($teamName == 'ss_fabrication_team') {
                if (!empty($employeeId)) {
                    $query->where('ss_fabrication_emp_id', $employeeId);
                }
            } else {
                if (!empty($employeeId)) {
                    $query->where('ms_fabrication_emp_id', $employeeId);
                }
            }
        }

        if (!empty($quotationId)) {
            $query->where('quotation_id', $quotationId);
        }

        if (!empty($fromdate) && !empty($todate)) {
            $query->whereHas('quotation', function ($q) use ($fromdate, $todate) {
                $q->whereBetween('quotation_date', [$fromdate, $todate]);
            });
        }

        $quotationProducts = $query->get();

        $data = $quotationProducts->map(function ($qp) use ($teamName) {
            return [
                'employee_name' => ($teamName == 'ss_fabrication_team' ? $qp->ssFabricationEmployee?->name : $qp->msFabricationEmployee?->name) ?? '-',
                'quotation_no' => $qp->quotation?->quotation_no ?? '-',
                'product_name' => $qp->product?->product_name ?? '-',
                'team_name' => $teamName == 'ss_fabrication_team' ? 'SS Fabrication Team' : 'MS Fabrication Team',
                'product_qty' => $qp->quantity ?? 0,
            ];
        });

        $writer = SimpleExcelWriter::create(storage_path('app/public/quotation_products_report_' . date('Y-m-d_H-i-s') . '.xlsx'));

        // Add headers
        $writer->addRow([
            'S.No',
            'Employee Name',
            'Quotation No',
            'Product Name',
            'Team Name',
            'Product Quantity',
        ]);

        // Add data rows
        foreach ($data as $index => $row) {
            $writer->addRow([
                $index + 1,
                $row['employee_name'],
                $row['quotation_no'],
                $row['product_name'],
                $row['team_name'],
                $row['product_qty'],
            ]);
        }

        $writer->close();

        return response()->download(storage_path('app/public/quotation_products_report_' . date('Y-m-d_H-i-s') . '.xlsx'))->deleteFileAfterSend();
    }

    public function exportQuotationProductsReportPdf(Request $request)
    {
        $employeeId = $request->employeeIds;
        $quotationId = $request->quotationIds;
        $teamName = $request->teamName;
        $fromdate = $request->fromdate;
        $todate = $request->todate;

        $query = QuotationProducts::with('quotation', 'msFabricationEmployee', 'ssFabricationEmployee', 'product');

        if (!empty($teamName)) {
            if ($teamName == 'ss_fabrication_team') {
                if (!empty($employeeId)) {
                    $query->where('ss_fabrication_emp_id', $employeeId);
                }
            } else {
                if (!empty($employeeId)) {
                    $query->where('ms_fabrication_emp_id', $employeeId);
                }
            }
        }

        if (!empty($quotationId)) {
            $query->where('quotation_id', $quotationId);
        }

        if (!empty($fromdate) && !empty($todate)) {
            $query->whereHas('quotation', function ($q) use ($fromdate, $todate) {
                $q->whereBetween('quotation_date', [$fromdate, $todate]);
            });
        }

        $quotationProducts = $query->get();

        $data = $quotationProducts->map(function ($qp) use ($teamName) {
            return [
                'employee_name' => ($teamName == 'ss_fabrication_team' ? $qp->ssFabricationEmployee?->name : $qp->msFabricationEmployee?->name) ?? '-',
                'quotation_no' => $qp->quotation?->quotation_no ?? '-',
                'product_name' => $qp->product?->product_name ?? '-',
                'team_name' => $teamName == 'ss_fabrication_team' ? 'SS Fabrication Team' : 'MS Fabrication Team',
                'product_qty' => $qp->quantity ?? 0,
            ];
        });

        $title = 'Quotation Products Report - ' . date('d-m-Y H:i:s');

        $pdf = Pdf::loadView('pages.reports.exports.quotation_products_report_pdf', compact('data', 'title'));

        return $pdf->stream('quotation_products_report_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    public function exportSalesStockReportExcel(Request $request)
    {
        $quotation_id = $request->quotationIds;
        $fromdate = $request->fromdate;
        $todate = $request->todate;

        $query = Quotation::with(['customer']);

        // Quotation filter
        if (!empty($quotation_id)) {
            $query->where('id', $quotation_id);
        }

        // Date range
        if (!empty($fromdate) && !empty($todate)) {
            $query->whereBetween('quotation_date', [$fromdate, $todate]);
        }

        $results = $query->get();

        $data = $results->map(function ($item) {
            $batch = \App\Models\QuotationBatch::whereJsonContains('quotation_ids', $item->id)->first();

            return [
                'id' => $item->id,
                'quotation_no' => $item->quotation_no,
                'quotation_date' => formatDate($item->quotation_date),
                'customer' => $item->customer?->customer_name,
                'batch_date' => $batch ? formatDate($batch->batch_date) : null,
                'total_collectable_amount' => $item->total_collectable_amount,
            ];
        });

        $directory = storage_path('app/public');
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $fileName = 'sales_stock_report_' . date('Y-m-d_H-i-s') . '.xlsx';
        $filePath = storage_path('app/public/' . $fileName);

        $writer = SimpleExcelWriter::create($filePath);

        // Add headers
        $writer->addRow([
            'S.No',
            'Quotation No',
            'Quotation Date',
            'Customer',
            'Batch',
            'Quotation Amount',
        ]);

        // Add data rows
        foreach ($data as $index => $row) {
            $writer->addRow([
                $index + 1,
                $row['quotation_no'],
                $row['quotation_date'],
                $row['customer'],
                $row['batch_date'],
                $row['total_collectable_amount'],
            ]);
        }

        $writer->close();

        if (file_exists($filePath)) {
            return response()->download($filePath)->deleteFileAfterSend();
        } else {
            return response()->json(['error' => 'File could not be created'], 500);
        }
    }

    public function exportSalesStockReportPdf(Request $request)
    {
        $quotation_id = $request->quotationIds;
        $fromdate = $request->fromdate;
        $todate = $request->todate;

        $query = Quotation::with(['customer']);

        // Quotation filter
        if (!empty($quotation_id)) {
            $query->where('id', $quotation_id);
        }

        // Date range
        if (!empty($fromdate) && !empty($todate)) {
            $query->whereBetween('quotation_date', [$fromdate, $todate]);
        }

        $results = $query->get();

        $data = $results->map(function ($item) {
            $batch = \App\Models\QuotationBatch::whereJsonContains('quotation_ids', $item->id)->first();

            return [
                'id' => $item->id,
                'quotation_no' => $item->quotation_no,
                'quotation_date' => formatDate($item->quotation_date),
                'customer' => $item->customer?->customer_name,
                'batch_date' => $batch ? formatDate($batch->batch_date) : null,
                'total_collectable_amount' => $item->total_collectable_amount,
            ];
        });

        $title = 'Sales Stock Report - ' . date('d-m-Y H:i:s');

        $pdf = Pdf::loadView('pages.reports.exports.sales_stock_report_pdf', compact('data', 'title'));

        return $pdf->stream('sales_stock_report_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    public function exportTaskReportExcel(Request $request)
    {
        $employeeIds = $request->employeeIds;
        $status = $request->status;
        $fromdate = $request->fromdate;
        $todate = $request->todate;

        $query = Task::with('assignedTo');

        // Status filter
        if (!empty($status)) {
            $query->where('status', $status);
        }

        // Employee filter
        if (!empty($employeeIds)) {
            $query->where('assigned_to', $employeeIds);
        }

        // Task date filter
        if (!empty($fromdate) && !empty($todate)) {
            $query->whereBetween('task_date', [$fromdate, $todate]);
        }

        $tasks = $query->get();

        $data = $tasks->map(function ($task) {
            return [
                'id' => $task->id,
                'task_title' => $task->task_title,
                'task_details' => $task->task_details,
                'assigned_to' => $task->assignedTo?->name ?? '-',
                'status' => $task->status,
                'task_date' => formatDate($task->task_date),
                'next_run_date' => $task->next_run_date ? formatDate($task->next_run_date) : '-',
            ];
        });

        $directory = storage_path('app/public');
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $fileName = 'task_report_' . date('Y-m-d_H-i-s') . '.xlsx';
        $filePath = storage_path('app/public/' . $fileName);

        $writer = SimpleExcelWriter::create($filePath);

        // Add headers
        $writer->addRow([
            'S.No',
            'Task Title',
            'Task Details',
            'Assigned To',
            'Status',
            'Task Date',
            'Next Run Date',
        ]);

        // Add data rows
        foreach ($data as $index => $row) {
            $writer->addRow([
                $index + 1,
                $row['task_title'],
                $row['task_details'],
                $row['assigned_to'],
                $row['status'],
                $row['task_date'],
                $row['next_run_date'],
            ]);
        }

        $writer->close();

        if (file_exists($filePath)) {
            return response()->download($filePath)->deleteFileAfterSend();
        } else {
            return response()->json(['error' => 'File could not be created'], 500);
        }
    }

    public function exportTaskReportPdf(Request $request)
    {
        $employeeIds = $request->employeeIds;
        $status = $request->status;
        $fromdate = $request->fromdate;
        $todate = $request->todate;

        $query = Task::with('assignedTo');

        // Status filter
        if (!empty($status)) {
            $query->where('status', $status);
        }

        // Employee filter
        if (!empty($employeeIds)) {
            $query->where('assigned_to', $employeeIds);
        }

        // Task date filter
        if (!empty($fromdate) && !empty($todate)) {
            $query->whereBetween('task_date', [$fromdate, $todate]);
        }

        $tasks = $query->get();

        $data = $tasks->map(function ($task) {
            return [
                'id' => $task->id,
                'task_title' => $task->task_title,
                'task_details' => $task->task_details,
                'assigned_to' => $task->assignedTo?->name ?? '-',
                'status' => $task->status,
                'task_date' => formatDate($task->task_date),
                'next_run_date' => $task->next_run_date ? formatDate($task->next_run_date) : '-',
            ];
        });

        $title = 'Task Report - ' . date('d-m-Y H:i:s');

        $pdf = Pdf::loadView('pages.reports.exports.task_report_pdf', compact('data', 'title'));

        return $pdf->stream('task_report_' . date('Y-m-d_H-i-s') . '.pdf');
    }
}
