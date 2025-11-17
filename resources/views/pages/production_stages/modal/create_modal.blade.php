<div class="modal fade" id="createProductionStageModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="createFormSubmit" class="form-horizontal m-t-10" method="POST" action="{{ route('production-stages.store') }}">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Production Stages</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

         <div class="form-group">
                                        <label> Process Team </label>
                                        <div>
                                            <select class="form-control" name="process_team_id">
                                                <option value="">-- Choose Option --</option>
                                                @if ($productionTeams = App\Models\ProcessTeam::get())
                                                    @foreach ($productionTeams as $productionTeam)
                                                    <option value="{{ $productionTeam->id }}">{{ $productionTeam->team_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                      <div class="form-group">
                                        <label> Name</label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="stage_name" placeholder=""/>
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
