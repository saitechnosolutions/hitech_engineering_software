<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\QuotationProducts;
use App\DataTables\TaskReportDataTable;
use App\DataTables\CollectionReportDataTable;
use App\Http\Resources\EmployeeReportCollection;
use App\DataTables\EmployeeWiseProductionReportDataTable;

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

        if(!empty($teamName))
        {
            if($teamName == 'ss_fabrication_team')
                {
                    if (!empty($employeeIds)) {
                        $quotationProducts->where('ss_fabrication_emp_id', $employeeIds);
                    }
                }
                else
                {
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

}
