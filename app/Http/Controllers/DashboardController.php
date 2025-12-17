<?php

namespace App\Http\Controllers;

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

        return view('pages.dashboard.index', compact('quotations', 'roleId', 'lastWeekData','currentWeekData', 'teamIds', 'roleName', 'employees', 'teamId', 'orderDetails', 'processTeams', 'completedQuotations', 'recentTasks', 'invoiceRequests',  'todayPaymentColledtedCount', 'activeOrdersCount', 'collectionPendingAmount', 'revenueAmount', 'retailOrdersCount', 'retailOrdersCountCompleted', 'inProductionCount'));
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
}
