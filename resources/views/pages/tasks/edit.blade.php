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
                        <div class="col-12">
                            <div class="card m-b-30">
                                <div class="card-header">
                                    <div style="display:flex;justify-content:space-between">
                                        <h4 class="mt-0 header-title">Edit Task</h4>
                                    </div>
                                </div>
                                <form id="createFormSubmit" class="form-horizontal m-t-10" method="POST" action="{{ route('tasks.update', $task->id) }}" enctype="multipart/form-data">

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-3">
                    <div class="form-group">
                                        <label>Title</label>
                                        <div>
                                            <input type="text" class="form-control" required name="task_title" value="{{ $task->title }}" />
                                        </div>
                                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                                        <label>Task Type</label>
                                        <div>
                                            <select class="form-control" name="task_type" id="task_type">
                                                <option value="">-- Choose Task --</option>
                                                <option value="single_time" @if($task->task_type == 'single_time') selected @endif>Single Time</option>
                                                <option value="repeating_task" @if($task->task_type == 'repeating_task') selected @endif>Repeating Task</option>
                                            </select>
                                        </div>
                                    </div>
                </div>
                <div class="col-lg-3" id="start_date">
                    <div class="form-group">
                                        <label>Start Date</label>
                                        <div>
                                            <input type="date" class="form-control" required name="task_date" value="{{ $task->task_date }}" placeholder=""/>
                                        </div>
                                    </div>
                </div>
                <div class="col-lg-3" id="end_date">
                    <div class="form-group">
                                        <label>End Date</label>
                                        <div>
                                            <input type="date" class="form-control" required name="end_date" value="{{ $task->end_date }}"  placeholder=""/>
                                        </div>
                                    </div>
                </div>
                <div class="col-lg-3" id="repeating_type">
                    <div class="form-group">
                                        <label>Repeating Type</label>
                                        <div>
                                            <select class="form-control" name="repeating_type" id="repeating_type">
                                                <option value="">-- Choose Repeating Type --</option>
                                                <option value="daily" @if($task->repeating_type == 'daily') selected @endif>Daily</option>
                                                <option value="monthly" @if($task->repeating_type == 'monthly') selected @endif>Monthly</option>
                                                <option value="weekly" @if($task->repeating_type == 'weekly') selected @endif>Weekly</option>
                                            </select>
                                        </div>
                                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                                        <label>Priority</label>
                                        <div>
                                            <select class="form-control" name="priority">
                                                <option value="">-- Choose Priority --</option>
                                                <option value="High" @if($task->priority == 'High') selected @endif>High</option>
                                                <option value="Low" @if($task->priority == 'Low') selected @endif>Low</option>
                                                <option value="Medium" @if($task->priority == 'Medium') selected @endif>Medium</option>
                                            </select>
                                        </div>
                                    </div>
                </div>


                <div class="col-lg-3">
                     <div class="form-group">
                                        <label>Assigned To</label>
                                         <div>
                                            <select class="form-control js-example-basic-single" name="assigned_to">
                                                <option value="">-- Select User --</option>
                                                @if($users)
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}" @if($task->assigned_to == $user->id) selected @endif>{{ $user->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                </div>
                <div class="col-lg-3">
                     <div class="form-group">
                                        <label>Upload Image</label>
                                                             <div>
                                            <input type="file" class="form-control"
                                                    parsley-type="text" name="upload_image[]" multiple placeholder=""/>
                                        </div>
                                    </div>

                                      @php
    $docs = $task->created_task_images ?? [];

    // If it's a JSON string, decode it
    if (is_string($docs)) {
        $decoded = json_decode($docs, true);
        $docs = is_array($decoded) ? $decoded : [];
    }
@endphp

@if(!empty($docs))
    @foreach ($docs as $file)
        @if(!empty($file))
            <a href="{{ asset($file) }}" download>
                {{ basename($file) }}
            </a><br>
        @endif
    @endforeach
@else
    <span>No files uploaded</span>
@endif
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                                        <label>Task Details</label>
                                        <div>
                                            <textarea class="form-control" name="task_details">{{ $task->task_details }}</textarea>
                                        </div>
                                    </div>
                </div>

            </div>



                                            <button type="submit" class="btn btn-primary" style="width:250px">Submit</button>
                                </div>
                                </form>
                            </div>
                        </div> <!-- end col -->
                    </div>
@endsection

@include('pages.bom.modal.create_modal')

@push('scripts')
<script src="/assets/js/task.js"></script>
@endpush
