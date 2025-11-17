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
                            <div class="card m-b-30">
                                <div class="card-header">
                                    <div style="display:flex;justify-content:space-between">
                                        <h4 class="mt-0 header-title">Customers</h4>
                                    <button type="button" class="btn btn-primary btn-sm" style="width:100px" data-toggle="modal" data-target="#createCustomerModal">
                                        Create
                                    </button>
                                    </div>
                                </div>


                                <div class="card-body">

                                    <div class="table-responsive">
                                          {{ $dataTable->table(['class' => 'table table-striped table-bordered dt-responsive nowrap']) }}
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
@endsection

@include('pages.customers.modal.create_modal')
@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
