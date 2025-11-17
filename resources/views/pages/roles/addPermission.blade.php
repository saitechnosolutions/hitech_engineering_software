@extends('layouts.app')

@section('main-content')

<div class="row mt-5">
    <div class="col-lg-12">


    <div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h4 class="align-content-lg-between">Role : {{ $role->name }}</h4>
            <a href="{{ URL::previous() }}" class="btn btn-primary">Back</a>
        </div>
    </div>
    <div class="card-body">
        <form class="forms-sample" action="{{ route('roles.givePermissionToRole', $role->id) }}" method="POST">
            @csrf
            @method('put')
            {{--  <div class="row">
                <label for="exampleInputUsername1" class="form-label">Permissions</label>
                @foreach ($permissions as $permission)
                <div class="col-lg-3">
                    <div class="mb-3">
                        <input type="checkbox" name="permission[]" value="{{ $permission->name }}" {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                        {{ $permission->name }}

                    </div>
                </div>
                @endforeach
            </div>  --}}

            <div class="card-body">
        <form class="forms-sample" action="{{ route('roles.givePermissionToRole', $role->id) }}" method="POST">
            @csrf
            @method('put')
            {{--  <div class="row">
                <label for="exampleInputUsername1" class="form-label">Permissions</label>
                @foreach ($permissions as $permission)
                <div class="col-lg-3">
                    <div class="mb-3">
                        <input type="checkbox" name="permission[]" value="{{ $permission->name }}" {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                        {{ $permission->name }}

                    </div>
                </div>
                @endforeach
            </div>  --}}
            @if($departments)
            <div class="row">

                    @foreach ($departments as $department)
                    <div class="col-lg-4">
    <div class="accordion mt-2" id="accordionExample">
        <div class="card">
            <div class="card-header" id="headingOne" style="background:beige">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left collapsed" type="button"
                        data-toggle="collapse"
                        data-target="#{{ removeSpaceAddHyphen($department->navbar_section) }}"
                        aria-expanded="false"
                        aria-controls="{{ removeSpaceAddHyphen($department->navbar_section) }}">

                        <b>{{ $department->navbar_section }}</b>

                        @php
                            $checkedPrinted = false;
                        @endphp

                        @if($permissions = App\Models\Permission::where('navbar_id', $department->id)->get())
                            @foreach ($permissions as $permission)
                                @if(in_array($permission->id, $rolePermissions) && !$checkedPrinted)
                                    <i class="fa fa-check"
                                        style="font-size:20px;margin-left:20px;color:green"
                                        aria-hidden="true"></i>
                                    @php
                                        $checkedPrinted = true;
                                    @endphp
                                @endif
                            @endforeach
                        @endif
                    </button>
                </h2>
            </div>

            <div id="{{ removeSpaceAddHyphen($department->navbar_section) }}"
                class="collapse"
                aria-labelledby="headingOne"
                data-parent="#accordionExample">

                <div class="card-body">
                    <div class="row">
                        @if($permissions = App\Models\Permission::where('navbar_id', $department->id)->get())
                            @foreach ($permissions as $permission)
                                <div class="mb-3 ml-5">
                                    <input type="checkbox"
                                        class="form-check-input"
                                        name="permission[]"
                                        value="{{ $permission->name }}"
                                        {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                    {{ $permission->name }}
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

                @endforeach

            </div>

            @endif


            <button type="submit" class="btn btn-primary mt-4">Submit</button>
        </form>



    </div>


        </form>
    </div>
  </div>
  </div>
</div>
@endsection
