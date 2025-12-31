@extends('layouts.app')
@section('main-content')
<x-breadcrumb
    :items="[
        ['label' => 'Hi-tech Engineering', 'url' => '#'],
        ['label' => 'Pages', 'url' => '#'],
        ['label' => 'Task Report']
    ]"
    title="" />

<div class="row mt-3">

     <div class="col-12">
                            <div class="card m-b-30">

                                <div class="card-header">

                                    <div style="display:flex;justify-content:space-between">
                                        <h6>Filter</h6>
                                    </div>

                                </div>
                                <div class="card-body">
                                    <form  class="reportSubmission" action="{{ route('taskReportFilter') }}" id="taskReportFilter">
                                        @csrf
                                    <div class="row">
                                        <div class="col-lg-3">
                                             <div class="form-group">
                                                                        <label>Employee Name</label>
                                                                        <div>
                                                                            <select class="form-control js-example-basic-single" name="employeeIds" id="employeeIds" style="width:350px">
                                                                                <option value="">-- Choose Option --</option>
                                                                                @if($employees = App\Models\User::get())
                                                                                    @foreach ($employees as $employee)
                                                                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                                                                    @endforeach
                                                                                @endif
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                        </div>


                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                                        <label>Status</label>
                                                                        <div>
                                                                            <select class="form-control js-example-basic-single" name="status" id="status" style="width:350px">
                                                                                <option value="">-- Choose Status --</option>
                                                                                <option value="completed">Completed</option>
                                                                                <option value="pending">Pending</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                        </div>
                                                                    <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label>From Date</label>
                                                                        <input type="date" class="form-control" name="fromdate" id="fromDate">
                                                                    </div>
                                        </div>

                                                                    <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label>Todate</label>
                                                                        <input type="date" class="form-control" name="todate" id="toDate">
                                                                    </div>
                                        </div>

                                        <div class="col-lg-3">
                                            <button type="submit" class="btn btn-danger">Filter</button>
                                        </div>

                                        </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div> <!-- end col -->

                        <div class="col-12 p-0">
                            <div class="card m-b-30">
                                <div class="card-header">
                                    <div style="display:flex;justify-content:space-between">
                                        <h4 class="mt-0 header-title">Task Report</h4>
                                        <div>
                                            <div class='dropdown'>
                                            <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                                        <i class='fa fa-download' aria-hidden='true'></i>
                                            </button>
                                            <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                                <a class='dropdown-item' id="excelExport" href="/task-report-export-excel" target="_blank">Excel</a>
                                                <a class='dropdown-item' id="pdfExport" href="/task-report-export-pdf" target="_blank">PDF</a>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="card-body">

                                    <div class="table-responsive">
                                        {{ $dataTable->table(['class' => 'table table-striped table-bordered dt-responsive nowrap']) }}
                                    </div>


                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div>


@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
<script src="/assets/reports/task-report.js"></script>
@endpush

