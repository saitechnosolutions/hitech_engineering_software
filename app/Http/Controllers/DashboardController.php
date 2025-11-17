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

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May'];
        $sales = [1500, 2000, 1800, 2200, 2600];
        $todayDate = date("Y-m-d");
        $todayPaymentColledtedCount = PaymentDetails::where('payment_date', $todayDate)->count();
        $activeOrdersCount = Quotation::whereIn('production_status', ['production_moved', 'ongoing'])->count();
        $revenueAmount = Quotation::where('production_status', 'completed')->count();
        $collectionPendingAmount = Quotation::whereIn('production_status', ['production_moved', 'ongoing'])->sum('total_collectable_amount');

        return view('pages.dashboard.index', compact('quotations', 'roleId', 'teamIds', 'roleName', 'employees', 'teamId', 'orderDetails', 'processTeams', 'completedQuotations', 'recentTasks', 'invoiceRequests', 'months', 'sales', 'todayPaymentColledtedCount', 'activeOrdersCount', 'collectionPendingAmount', 'revenueAmount'));
    }

    public function dashboardTl()
    {
        return view('pages.dashboard.dashboard_tl');
    }
}