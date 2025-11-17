<div class="modal fade" id="createLrDocumentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
     <form id="createFormSubmit" class="form-horizontal m-t-10" method="POST" action="{{ route('lr-documents.store') }}" enctype="multipart/form-data">
        @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload LR Document</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
                                            <label>Select Quotation</label>
                                            <select class="form-control js-example-basic-single" name="quotation_id" style="width:100%">
                                                    <option>-- Choose Quotation No --</option>
                                                    @if($quotations = App\Models\Quotation::get())
                                                        @foreach ($quotations as $quotation)
                                                            <option value="{{ $quotation->id }}">{{ $quotation->quotation_no }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                        </div>
                                       
                                        <div class="form-group">
                                            <label>Upload Documents</label>
                                            <input type="file" name="reference_images[]" class="form-control" multiple>
                                        </div>
                                        <div class="form-group">
                                            <label>Remarks</label>
                                            <textarea class="form-control" name="remarks"></textarea>
                                        </div>
                                      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>
     </form>
  </div>
</div>
