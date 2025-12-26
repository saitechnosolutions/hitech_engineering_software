<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\QuotationProducts;
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
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\SimpleExcel\SimpleExcelWriter;

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

        $data = [
            'results' => $results,
            'title' => 'Collection Report'
        ];

        $pdf = Pdf::loadView('pages.reports.exports.collection_report_pdf', $data);
        return $pdf->stream('collection_report_' . date('Y-m-d_H-i-s') . '.pdf');
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

        $rows = [];
        $rows[] = [
            'S.No',
            'Company Name',
            'Batch',
            'Quotation No',
            'Quotation Date',
            'RM',
            'Quotation Amount',
            'Received Amount',
            'Pending Amount'
        ];

        foreach ($results as $index => $item) {
            $batch = \App\Models\QuotationBatch::whereJsonContains('quotation_ids', $item->id)->first();
            $rows[] = [
                $index + 1,
                $item->customer?->customer_name,
                $batch ? formatDate($batch->batch_date) : '-',
                $item->quotation_no,
                formatDate($item->quotation_date),
                $item->customer?->employee?->name,
                $item->total_collectable_amount,
                number_format($item->payments->sum('amount'), 2),
                number_format($item->total_collectable_amount - $item->payments->sum('amount'), 2)
            ];
        }

        $filename = 'collection_report_' . date('Y-m-d_H-i-s') . '.xlsx';
        $filePath = storage_path('app/' . $filename);

        SimpleExcelWriter::create($filePath)->addRows($rows);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
