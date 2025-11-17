<div class="modal fade" id="createPermissionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="createFormSubmit" class="form-horizontal m-t-10" method="POST" action="{{ route('permission.store') }}">
        @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Permissions</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

                                    <div class="form-group">
                                        <label>Navbar Section</label>
                                        <div>
                                            <select class="form-control" name="navbar_section">
                                                <option value="">-- Choose Section --</option>
                                                @if ($navbarSections = App\Models\NavbarSection::get())
                                                    @foreach ($navbarSections as $navbarSection)
                                                        <option value="{{ $navbarSection->id }}">{{ $navbarSection->navbar_section }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                      <div class="form-group">
                                        <label>Permission Name</label>
                                        <div>
                                            <input type="text" class="form-control" name="name" required
                                                    parsley-type="text" placeholder="Enter Permission Name"/>
                                        </div>
                                    </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save </button>
      </div>
    </div>
    </form>
  </div>
</div>
