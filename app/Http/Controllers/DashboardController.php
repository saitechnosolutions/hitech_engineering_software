<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaymentFilterCollection;
use App\Models\Task;
use App\Models\Employee;
use App\Models\Quotation;
use App\Models\ProcessTeam;
use Illuminate\Http\Request;
use App\Models\InvoiceRequest;
use App\Models\PaymentDetails;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Models\QuotationProductionStages;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\SimpleExcel\SimpleExcelWriter;
use App\Exports\TlDashboardExport;

class DashboardController extends Controller
{
    public function index()
    {

        $roleId = Auth::user()->roles->first()->id;
        // $roleId = 1;
        $role = Role::find($roleId);
        $roleName = $role->name;

        $permissions = $role->permissions;

        $permissionNames = $role->permissions->where('navbar_id', 16)->pluck('name');

        $teamIds = ProcessTeam::whereIn('team_slug', $permissionNames)->pluck('id');

        $quotations = Quotation::with('quotationProducts')->where('production_status', '!=', 'completed')->get();

        $teamId = Auth::user()->team_id;
        $employees = Employee::where('team_id', $teamId)->get();

        $orderDetails = Quotation::with('payments')->get()->take(10);
        $completedQuotations = Quotation::where('production_status', 'completed')->get()->take(10);
        $processTeams = ProcessTeam::with('quotationProductionStages')->get();
        $recentTasks = Task::get()->take(10);
        $invoiceRequests = InvoiceRequest::where('status', 'pending')->get();

        $todayDate = date("Y-m-d");
        $todayPaymentColledtedCount = PaymentDetails::where('payment_date', $todayDate)->get();
        $activeOrdersCount = Quotation::whereIn('production_status', ['not_moved', 'production_moved', 'ongoing'])->count();
        $inProductionCount = Quotation::whereIn('production_status', ['production_moved', 'ongoing'])->count();
        $retailOrdersCount = Quotation::whereIn('production_status', ['production_moved', 'ongoing'])->where('quotation_type', 'retail')->count();
        $retailOrdersCountCompleted = Quotation::where('production_status', 'completed')->where('quotation_type', 'retail')->count();
        $revenueAmount = Quotation::where('production_status', 'completed')->sum('total_collectable_amount');
        $collectionPendingAmount = Quotation::whereIn('production_status', ['production_moved', 'ongoing'])->sum('total_collectable_amount');

        $lastWeekData = [15000, 3000, 30000, 22000, 3000, 17000, 65000];
        $currentWeekData = [10005, 20002, 10009, 20005, 2000, 10004, 10001];

        return view('pages.dashboard.index', compact('quotations', 'roleId', 'lastWeekData', 'currentWeekData', 'teamIds', 'roleName', 'employees', 'teamId', 'orderDetails', 'processTeams', 'completedQuotations', 'recentTasks', 'invoiceRequests',  'todayPaymentColledtedCount', 'activeOrdersCount', 'collectionPendingAmount', 'revenueAmount', 'retailOrdersCount', 'retailOrdersCountCompleted', 'inProductionCount'));
    }

    public function dashboardTl()
    {
        return view('pages.dashboard.dashboard_tl');
    }

    public function paymentCollectionToday()
    {
        $todaydate = date("Y-m-d");
        $paymentDetails = PaymentDetails::with('quotation')->where('payment_date', $todaydate)->get();

        return view('pages.dashboard.payment_collection_today', compact('paymentDetails'));
    }

    public function collectionPending()
    {
        $collectionPendings = Quotation::with('payments')->whereIn('production_status', ['production_moved', 'ongoing'])->get();

        return view('pages.dashboard.collection_pending', compact('collectionPendings'));
    }

    public function revenueDetails()
    {
        $revenueDetails = Quotation::where('production_status', 'completed')->get();

        return view('pages.dashboard.revenue_details', compact('revenueDetails'));
    }

    public function retailActiveOrders()
    {
        $retailActiveOrders = Quotation::whereIn('production_status', ['production_moved', 'ongoing'])->where('quotation_type', 'retail')->get();

        return view('pages.dashboard.retail-active-orders', compact('retailActiveOrders'));
    }

    public function retailCompletedOrders()
    {
        $retailCompletedOrders = Quotation::where('production_status', 'completed')->where('quotation_type', 'retail')->get();

        return view('pages.dashboard.retail_completed_orders', compact('retailCompletedOrders'));
    }

    // public function paymentPendingDetails()
    // {
    //     $paymentPendingDetails = Quotation::where('production_status', 'completed')->get();
    // }

    public function paymentReportFilter(Request $request)
    {
        $quotationId = $request->quotationId;
        $fromDate    = $request->fromdate;
        $toDate      = $request->todate;
        $companyName = $request->company_name;
        $rm          = $request->rm;

        $payments = PaymentDetails::with([
            'quotation.customer',
            'rm'
        ])

            // Filter by quotation
            ->when($quotationId, function ($q) use ($quotationId) {
                $q->where('quotation_id', $quotationId);
            })

            // Filter by date
            ->when($fromDate && $toDate, function ($q) use ($fromDate, $toDate) {
                $q->whereBetween('payment_date', [$fromDate, $toDate]);
            })

            //  Filter by Company Name (Customer)
            ->when($companyName, function ($q) use ($companyName) {
                $q->whereHas('quotation.customer', function ($query) use ($companyName) {
                    $query->where('customer_name', $companyName);
                });
            })

            //  Filter by RM
            ->when($rm, function ($q) use ($rm) {
                $q->whereHas('rm', function ($query) use ($rm) {
                    $query->where('name', $rm);
                });
            })

            ->orderBy('payment_date', 'desc')
            ->get();

        $paymentDetails = new PaymentFilterCollection($payments);

        return response()->json([
            'status' => 'success',
            'data'   => $paymentDetails
        ]);
    }
    public function orderstatusReportFilter(Request $request)
    {
        $companyName    = $request->company_name;
        $fromDate       = $request->fromdate;
        $toDate         = $request->todate;
        $dispatchStatus = $request->dispatch_status;

        $query = Quotation::with([
            'customer:id,customer_name',
            'payments:id,quotation_id,amount'
        ]);

        // Company Filter
        if (!empty($companyName)) {
            $query->whereHas('customer', function ($q) use ($companyName) {
                $q->where('customer_name', $companyName);
            });
        }

        // Date Filter
        if (!empty($fromDate) && !empty($toDate)) {
            $query->whereBetween('quotation_date', [
                $fromDate,
                $toDate
            ]);
        }

        // Dispatch Status Filter
        if (!empty($dispatchStatus)) {
            $query->where('dispatch_status', $dispatchStatus);
        }

        $orderDetails = $query
            ->latest('id')
            ->take(50)
            ->get();

        $data = $orderDetails->map(function ($order) {
            // Production Status Badge Text
            $statusText = removeUnderscoreText($order->production_status ?? 'pending');

            // Dispatch Status Badge
            $deliveryBadge = match ($order->dispatch_status) {
                'completed'            => '<span class="badge bg-success">Completed</span>',
                'partialy_dispatched'  => '<span class="badge bg-info">Partially Dispatched</span>',
                'not_moved'            => '<span class="badge bg-danger">Not Moved</span>',
                'production_moved'     => '<span class="badge bg-primary">Production Moved</span>',
                default                => '<span class="badge bg-warning">Ongoing</span>',
            };

            return [
                'quotation_no'   => $order->quotation_no ?? '-',
                'customer_name'  => $order->customer?->customer_name ?? '-',
                'status'         => $statusText,
                'delivery'       => $deliveryBadge,
                'value'          => '₹' . number_format($order->total_collectable_amount ?? 0, 2),
                'payments'       => '₹' . number_format($order->payments->sum('amount') ?? 0, 2),
            ];
        });

        return response()->json([
            'status' => $data->isEmpty() ? 'empty' : 'success',
            'data'   => $data
        ]);
    }
    public function Quotefilter(Request $request)
    {
        $query = Quotation::with('customer')
            ->where('production_status', 'completed');

        // Filter by company name
        if ($request->company_name) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('customer_name', $request->company_name);
            });
        }

        // Filter by date range
        if ($request->fromdate && $request->todate) {
            $query->whereBetween('quotation_date', [$request->fromdate, $request->todate]);
        } elseif ($request->fromdate) {
            $query->whereDate('quotation_date', '>=', $request->fromdate);
        } elseif ($request->todate) {
            $query->whereDate('quotation_date', '<=', $request->todate);
        }

        $quotations = $query->orderBy('quotation_date', 'desc')->get();

        $data = $quotations->map(function ($q) {
            return [
                'quotation_no' => $q->quotation_no,
                'customer_name' => $q->customer?->customer_name,
                'status' => 'Completed',
                'value' => '₹' . number_format($q->total_collectable_amount, 2),
                'date' => $q->quotation_date->format('d-m-Y'),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function exportTlDashboardPdf(Request $request)
    {
        $roleId = Auth::user()->roles->first()->id;
        $role = Role::find($roleId);
        $roleName = $role->name;
        $permissions = $role->permissions;
        $permissionNames = $role->permissions->where('navbar_id', 16)->pluck('name');
        $teamIds = ProcessTeam::whereIn('team_slug', $permissionNames)->pluck('id');

        $quotations = Quotation::with('quotationProducts')->where('production_status', '!=', 'completed');

        if ($roleName == 'Team Leader - Dispatch Team') {
            $userId = Auth::user()->id;
            $quotations = $quotations->where('dispatch_team_id', $userId);
        }

        // Filter by specific quotation if quotation_id is provided
        if ($request->has('quotation_id') && $request->quotation_id) {
            $quotations = $quotations->where('id', $request->quotation_id);
        }

        $quotations = $quotations->get();

        $filteredQuotations = collect();
        foreach ($quotations as $quotation) {
            $quotationCount = QuotationProductionStages::with('bom')
                ->where('quotation_id', $quotation->id)
                ->whereIn('team_id', $teamIds)
                ->where('status', 'pending')
                ->get()
                ->count();

            if ($quotationCount != 0) {
                $filteredQuotations->push($quotation);
            }
        }

        $data = [
            'quotations' => $filteredQuotations,
            'roleName' => $roleName,
            'teamIds' => $teamIds,
            'title' => 'TL Dashboard Report'
        ];

        $pdf = Pdf::loadView('pages.dashboard.exports.tl_dashboard_pdf', $data);
        return $pdf->stream('tl_dashboard_report_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    public function exportTlDashboardExcel(Request $request)
    {
        $roleId = Auth::user()->roles->first()->id;
        $role = Role::find($roleId);
        $roleName = $role->name;
        $permissions = $role->permissions;
        $permissionNames = $role->permissions->where('navbar_id', 16)->pluck('name');
        $teamIds = ProcessTeam::whereIn('team_slug', $permissionNames)->pluck('id');

        $quotations = Quotation::with('quotationProducts')->where('production_status', '!=', 'completed');

        if ($roleName == 'Team Leader - Dispatch Team') {
            $userId = Auth::user()->id;
            $quotations = $quotations->where('dispatch_team_id', $userId);
        }

        // Filter by specific quotation if quotation_id is provided
        if ($request->has('quotation_id') && $request->quotation_id) {
            $quotations = $quotations->where('id', $request->quotation_id);
        }

        $quotations = $quotations->get();

        $filteredQuotations = collect();
        foreach ($quotations as $quotation) {
            $quotationCount = QuotationProductionStages::with('bom')
                ->where('quotation_id', $quotation->id)
                ->whereIn('team_id', $teamIds)
                ->where('status', 'pending')
                ->get()
                ->count();

            if ($quotationCount != 0) {
                $filteredQuotations->push($quotation);
            }
        }

        $data = [
            'quotations' => $filteredQuotations,
            'roleName' => $roleName,
            'teamIds' => $teamIds,
            'title' => 'TL Dashboard Report'
        ];

        $export = new TlDashboardExport($filteredQuotations, $roleName, $teamIds);
        $rows = $export->getData();

        $filename = 'tl_dashboard_report_' . date('Y-m-d_H-i-s') . '.xlsx';
        $filePath = storage_path('app/' . $filename);

        SimpleExcelWriter::create($filePath)->addRows($rows);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
