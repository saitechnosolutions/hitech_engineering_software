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
                                        <h4 class="mt-0 header-title">BOM</h4>

                                    <a href="{{ route('bom.create') }}" class="btn btn-primary btn-sm">Create</a>
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

@include('pages.bom.modal.create_modal')

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
