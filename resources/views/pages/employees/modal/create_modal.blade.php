<div class="modal fade" id="createEmployeeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
     <form id="createFormSubmit" class="form-horizontal m-t-10" method="POST" action="{{ route('employees.store') }}">
        @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Employees</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

                                      <div class="form-group">
                                        <label>Employee Name</label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="name" placeholder=""/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>E-mail</label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="email" placeholder=""/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Allocate Team</label>
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
                                        <label>Mobile Number</label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="mobile_number" placeholder=""/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Address</label>
                                        <div>
                                            <textarea class="form-control" name="address"></textarea>
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
