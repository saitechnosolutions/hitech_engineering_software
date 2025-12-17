@extends('layouts.app')
@section('main-content')
<x-breadcrumb
    :items="[
        ['label' => 'Hi-tech Engineering', 'url' => '#'],
        ['label' => 'Pages', 'url' => '#'],
        ['label' => 'Tasks']
    ]"
    title="" />

  <div class="row mt-3">
                    <div class="col-md-3 col-lg-3 col-xl-3" >
                        <div class="card m-b-30 option-box" id="option_1">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="col-12 align-self-center text-center">
                                        <div class="m-l-10">
                                            <h5 class="mt-0 ">{{ $receivedTaskPending->count() }}</h5>
                                            <p class="mb-0 "><b>Received Tasks (Pending)</b></p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3" >
                        <div class="card m-b-30 option-box" id="option_2">
                            <div class="card-body">
                                <div class="d-flex flex-row">

                                    <div class="col-12 align-self-center text-center">
                                        <div class="m-l-10">
                                            <h5 class="mt-0 ">{{ $receivedTaskCompleted->count() }}</h5>
                                            <p class="mb-0 "><b>Received Tasks (Completed)</b></p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3" >
                        <div class="card m-b-30 option-box" id="option_3">
                            <div class="card-body">
                                <div class="d-flex flex-row">

                                    <div class="col-12 align-self-center text-center">
                                        <div class="m-l-10">
                                            <h5 class="mt-0 ">{{ $assignedTaskPendings->count() }}</h5>
                                            <p class="mb-0 "><b>Assiged Tasks (Pending)</b></p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="col-md-6 col-lg-6 col-xl-3" >
                        <div class="card m-b-30 option-box" id="option_4">
                            <div class="card-body">
                                <div class="d-flex flex-row">

                                    <div class="col-12 align-self-center text-center">
                                        <div class="m-l-10">
                                            <h5 class="mt-0 ">{{ $assignedTaskCompletes->count() }}</h5>
                                            <p class="mb-0 "><b>Assiged Tasks (Completed)</b></p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

            </div>

          <div class="row mt-3" id="option_1_data">
                        <div class="col-12">
                            <div class="card m-b-30">
                                <div class="card-header">
                                    <div style="display:flex;justify-content:space-between">
                                        <h4 class="mt-0 header-title">Received Tasks (Pending)</h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table table-striped table-bordered dt-responsive nowrap w-100" id="datatable">
                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Task Title</th>
                                                    <th>Task Type</th>
                                                    <th>Task Date</th>
                                                    <th>Task Details</th>
                                                    <th>Priority</th>
                                                    <th>Status</th>
                                                    <th>Assigned To</th>
                                                    <th>Credted By</th>
                                                    <th>Created Task Image</th>
                                                    <th>Completed Task Image</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @if($receivedTaskPending)
                                                @php
                                                    $i=1;
                                                @endphp
                                                    @foreach ($receivedTaskPending as $receivedTaskPen)
                                                        <tr>
                                                            <td>{{ $i++ }}</td>
                                                            <td>{{ $receivedTaskPen->title }}</td>
                                                            <td>{{ removeUnderscoreText($receivedTaskPen->task_type) }}</td>
                                                            <td>{{ formatDate($receivedTaskPen->task_date) }}</td>
                                                            <td>{{ $receivedTaskPen->task_details }}</td>
                                                            <td>{{ $receivedTaskPen->priority }}</td>
                                                            <td>{{ $receivedTaskPen->status }}</td>
                                                            <td>{{ $receivedTaskPen->assignedTo->name }}</td>
                                                            <td>{{ $receivedTaskPen->createdBy->name }}</td>
                                                            <td><img src="{{ $receivedTaskPen->create_task_image }}" class="img-fluid" style="width:70px"></td>
                                                            <td><img src="{{ $receivedTaskPen->comple_task_image }}" class="img-fluid" style="width:70px"></td>
                                                            <td>
                                                                <div class='dropdown'>
            <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                <i class='fa fa-cog' aria-hidden='true'></i>
            </button>
            <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                <a class='dropdown-item updateTaskStatus' data-id="{{ $receivedTaskPen->id }}">Update Status</a>
            </div>
            </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> <!-- end col -->
            </div>

                    <div class="row mt-3" id="option_2_data">
                        <div class="col-12">
                            <div class="card m-b-30">
                                <div class="card-header">
                                    <div style="display:flex;justify-content:space-between">
                                        <h4 class="mt-0 header-title">Received Task (Completed)</h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                         <table class="table table-striped table-bordered dt-responsive nowrap w-100" id="dataTable_two">
                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Title</th>
                                                    <th>Task Type</th>
                                                    <th>Task Date</th>
                                                    <th>Task Details</th>
                                                    <th>Priority</th>
                                                    <th>Status</th>
                                                    <th>Assigned To</th>
                                                    <th>Credted By</th>
                                                    <th>Created Task Image</th>
                                                    <th>Completed Task Image</th>
                                                    <th>Completed Date/Time</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @if($receivedTaskCompleted)
                                                @php
                                                    $i=1;
                                                @endphp
                                                    @foreach ($receivedTaskCompleted as $receivedTaskComplete)
                                                        <tr>
                                                            <td>{{ $i++ }}</td>
                                                            <td>{{ $receivedTaskComplete->title }}</td>
                                                            <td>{{ removeUnderscoreText($receivedTaskComplete->task_type) }}</td>
                                                            <td>{{ formatDate($receivedTaskComplete->task_date) }}</td>
                                                            <td>{{ $receivedTaskComplete->task_details }}</td>
                                                            <td>{{ $receivedTaskComplete->priority }}</td>
                                                            <td>{{ $receivedTaskComplete->status }}</td>
                                                            <td>{{ $receivedTaskComplete->assignedTo->name }}</td>
                                                            <td>{{ $receivedTaskComplete->createdBy->name }}</td>
                                                            <td><img src="{{ $receivedTaskComplete->create_task_image }}" class="img-fluid" style="width:70px"></td>
                                                            <td><img src="{{ $receivedTaskComplete->comple_task_image }}" class="img-fluid" style="width:70px"></td>
                                                            <td>{{ $receivedTaskComplete->completed_task_timestamp }}</td>

                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                    </table>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div>

                     <div class="row mt-3" id="option_3_data">
                        <div class="col-12">
                            <div class="card m-b-30">
                                <div class="card-header">
                                    <div style="display:flex;justify-content:space-between">
                                        <h4 class="mt-0 header-title">Assigned Task (Pending)</h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                         <table class="table table-striped table-bordered dt-responsive nowrap w-100" id="dataTable_three">
                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Title</th>
                                                    <th>Task Type</th>
                                                    <th>Task Date</th>
                                                    <th>Task Details</th>
                                                    <th>Priority</th>
                                                    <th>Status</th>
                                                    <th>Assigned To</th>
                                                    <th>Credted By</th>
                                                    <th>Created Task Image</th>
                                                    <th>Completed Task Image</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @if($assignedTaskPendings)
                                                @php
                                                    $i=1;
                                                @endphp
                                                    @foreach ($assignedTaskPendings as $assignedTaskPending)
                                                        <tr>
                                                            <td>{{ $i++ }}</td>
                                                            <td>{{ $assignedTaskPending->title }}</td>
                                                            <td>{{ removeUnderscoreText($assignedTaskPending->task_type) }}</td>
                                                            <td>{{ formatDate($assignedTaskPending->task_date) }}</td>
                                                            <td>{{ $assignedTaskPending->task_details }}</td>
                                                            <td>{{ $assignedTaskPending->priority }}</td>
                                                            <td>{{ $assignedTaskPending->status }}</td>
                                                            <td>{{ $assignedTaskPending->assignedTo->name }}</td>
                                                            <td>{{ $assignedTaskPending->createdBy->name }}</td>
                                                            <td><img src="{{ $assignedTaskPending->create_task_image }}" class="img-fluid" style="width:40px"></td>
                                                            <td><img src="{{ $assignedTaskPending->comple_task_image }}" class="img-fluid" style="width:40px"></td>
                                                            <td>
                                                                <div class='dropdown'>
            <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                <i class='fa fa-cog' aria-hidden='true'></i>
            </button>
            <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                <a class='dropdown-item' >Edit</a>
                <a class='dropdown-item' >Delete</a>
            </div>
            </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                    </table>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div>

                     <div class="row mt-3" id="option_4_data">
                        <div class="col-12">
                            <div class="card m-b-30">
                                <div class="card-header">
                                    <div style="display:flex;justify-content:space-between">
                                        <h4 class="mt-0 header-title">Assigned Task (Completed)</h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                         <table class="table table-striped table-bordered dt-responsive nowrap w-100" id="dataTable_five">
                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Title</th>
                                                    <th>Task Type</th>
                                                    <th>Task Date</th>
                                                    <th>Task Details</th>
                                                    <th>Priority</th>
                                                    <th>Status</th>
                                                    <th>Assigned To</th>
                                                    <th>Credted By</th>
                                                    <th>Created Task Image</th>
                                                    <th>Completed Task Image</th>
                                                    <th>Completed Date/Time</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @if($assignedTaskCompletes)
                                                @php
                                                    $i=1;
                                                @endphp
                                                    @foreach ($assignedTaskCompletes as $assignedTaskComplete)
                                                        <tr>
                                                            <td>{{ $i++ }}</td>
                                                            <td>{{ $assignedTaskComplete->title }}</td>
                                                            <td>{{ removeUnderscoreText($assignedTaskComplete->task_type) }}</td>
                                                            <td>{{ formatDate($assignedTaskComplete->task_date) }}</td>
                                                            <td>{{ $assignedTaskComplete->task_details }}</td>
                                                            <td>{{ $assignedTaskComplete->priority }}</td>
                                                            <td>{{ $assignedTaskComplete->status }}</td>
                                                            <td>{{ $assignedTaskComplete->assignedTo->name }}</td>
                                                            <td>{{ $assignedTaskComplete->createdBy->name }}</td>
                                                            <td><img src="{{ $assignedTaskComplete->create_task_image }}" class="img-fluid" style="width:40px"></td>
                                                            <td><img src="{{ $assignedTaskComplete->comple_task_image }}" class="img-fluid" style="width:40px"></td>
                                                            <td>
                                                                {{ $assignedTaskComplete->completed_task_timestamp }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                    </table>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div>
@endsection

@include('pages.tasks.modal.update-task-status')

@push('scripts')
        <script src="/assets/js/quotation.js"></script>


        <script>
            $(document).on("click", ".updateTaskStatus", function(){
                var taskId = $(this).data("id");

                $("#updateTaskStatusModal").modal('show');

                $("#task_id").val(taskId);
            })
        </script>

@endpush
