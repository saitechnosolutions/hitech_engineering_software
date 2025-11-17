<div class="modal fade" id="createBatchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="createFormSubmit" class="form-horizontal m-t-10" method="POST" action="{{ route('quotation_batch.store') }}">
                    @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Batch Creation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

                                    <div class="form-group">
                                        <label>Batch Date</label>
                                        <div>
                                            <input type="date" class="form-control" name="batch_date" required>
                                        </div>
                                    </div>

                                      <div class="form-group">
                                        <label>Select Quotations</label>
                                        <div>
                                            <select class="form-control js-example-basic-single" name="quotation_ids[]" style="width:450px" multiple required>
                                                <option value="">-- Choose Quotation --</option>
                                                @php
                                                    $quotations = App\Models\Quotation::where('is_production_moved', 'no')->orderBy('id', 'Desc')->get();
                                                @endphp
                                                @foreach ($quotations as $quotation)
                                                    <option value="{{ $quotation->id }}">{{ $quotation->quotation_no }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Priority</label>
                                        <div>
                                            <select class="form-control" name="priority" required>
                                                <option value="">-- Choose Option --</option>
                                                <option value="priority_1">P1</option>
                                                <option value="priority_2">P2</option>
                                                <option value="priority_3">P3</option>
                                                <option value="priority_4">P4</option>
                                                <option value="priority_5">P5</option>
                                            </select>
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
