<div class="modal fade" id="productionHistoryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="createFormSubmit" class="form-horizontal m-t-10" method="POST" action="{{ route('production.updateStatus') }}">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update Production Status</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
         <div class="form-group">
                    <label>Date</label>
                        <div>
                            <input type="date" class="form-control" required
                                                    parsley-type="text" name="date" value="{{ date('Y-m-d') }}" placeholder=""/>
                        </div>
            </div>

             <div class="form-group">
                    <label>Completed Quantity</label>
                        <div>
                            <input type="number" class="form-control" required
                                                    parsley-type="text" name="completed_quantity" id="completed_quantity" placeholder=""/>
                        </div>
            </div>
            <input type="hidden" name="production_id" id="production_id">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>
    </form>
  </div>
</div>
