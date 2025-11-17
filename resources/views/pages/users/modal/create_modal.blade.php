<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="createFormSubmit" class="form-horizontal m-t-10" method="POST" action="{{ route('users.store') }}">
        @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

                                      <div class="form-group">
                                        <label>Name</label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="name" placeholder=""/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>E-mail</label>
                                        <div>
                                            <input type="email" class="form-control" required
                                                    parsley-type="text" name="email" placeholder=""/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Mobile Number</label>
                                        <div>
                                            <input type="text" class="form-control" name="mobile_number" required
                                                    parsley-type="text" placeholder=""/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Team</label>
                                        <div>
                                           <select class="form-control" name="team_id">
                                                <option value="">-- Choose Option --</option>
                                                @if ($teams = App\Models\ProcessTeam::get())
                                                    @foreach ($teams as $team)
                                                        <option value="{{ $team->id }}">{{ $team->team_name }}</option>
                                                    @endforeach
                                                @endif
                                           </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Role</label>
                                        <div>
                                           <select class="form-control" name="role">
                                                <option value="">-- Choose Option --</option>
                                                @if ($roles = App\Models\Role::get())
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                                    @endforeach
                                                @endif
                                           </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <div>
                                            <input type="text" class="form-control" name="password" required
                                                    parsley-type="text" placeholder=""/>
                                        </div>
                                    </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </div>
    </form>
  </div>
</div>
