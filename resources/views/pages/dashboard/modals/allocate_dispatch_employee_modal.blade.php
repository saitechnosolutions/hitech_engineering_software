<div class="modal fade" id="allocatedispatchEmployeeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="createFormSubmit" class="form-horizontal m-t-10" method="POST" action="{{ route('production.allocateDispatchEmployee') }}">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Allocate Employee</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">


             <div class="form-group">
                    <label>Employee Name</label>
                        <div>
                            <select class="form-control quotationproduct js-example-basic-single" name="employee_id" style="width:100%">
                                                    <option>-- Choose Employee Name --</option>
                                                    @if($employees = App\Models\User::where('team_id', 9)->get())
                                                        @foreach ($employees as $employee)
                                                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                        </div>
            </div>

            <input type="hidden" name="dispatch_quotationid" id="dispatch_quotationid">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Allocate</button>
      </div>
    </div>
    </form>
  </div>
</div>
