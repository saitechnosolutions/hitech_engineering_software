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
                            <form id="createFormSubmit" class="form-horizontal m-t-10" method="POST" action="{{ route('permission.update', $permission->id) }}">
                            <div class="card m-b-30">
                                <div class="card-header">
                                    <div style="display:flex;justify-content:space-between">
                                        <h4 class="mt-0 header-title">Permissions</h4>

                                    </div>
                                </div>


                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                        <label>Navbar Section</label>
                                        <div>
                                            <select class="form-control" name="navbar_section">
                                                <option value="">-- Choose Section --</option>
                                                @if ($navbarSections = App\Models\NavbarSection::get())
                                                    @foreach ($navbarSections as $navbarSection)
                                                        <option value="{{ $navbarSection->id }}" @if($navbarSection->id == $permission->navbar_id) selected @endif>{{ $navbarSection->navbar_section }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                        </div>

                                        <div class="col-lg-6">
                                             <div class="form-group">
                                        <label>Permission Name</label>
                                        <div>
                                            <input type="text" class="form-control" name="name" required
                                                    parsley-type="text" value="{{ $permission->name }}" placeholder="Enter Permission Name"/>
                                        </div>
                                    </div>
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

@include('pages.permissions.modal.create_modal')

@push('scripts')

@endpush
