@extends('layouts.app')
@section('main-content')
<x-breadcrumb
    :items="[
        ['label' => 'Hi-tech Engineering', 'url' => '#'],
        ['label' => 'Pages', 'url' => '#'],
        ['label' => 'Users']
    ]"
    title="" />

 <div class="row mt-3">
                        <div class="col-12">
                            <form id="createFormSubmit" class="form-horizontal m-t-10" method="POST" action="{{ route('roles.update', $role->id) }}">
                            @csrf
                            <div class="card m-b-30">
                                <div class="card-header">
                                    <div style="display:flex;justify-content:space-between">
                                        <h4 class="mt-0 header-title">Roles</h4>

                                    </div>
                                </div>

                                <div class="card-body">
                                      <div class="form-group">
                                            <label>Role Name</label>
                                            <div>
                                                <input type="text" class="form-control" required
                                                        parsley-type="text" name="name" value="{{ $role->name }}" placeholder="Enter Role Name"/>
                                            </div>
                                        </div>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-success">Submit</button>
                                </div>
                            </div>
                        </form>
                        </div> <!-- end col -->
                    </div>
@endsection

@include('pages.roles.modal.create_modal')
@push('scripts')

@endpush

