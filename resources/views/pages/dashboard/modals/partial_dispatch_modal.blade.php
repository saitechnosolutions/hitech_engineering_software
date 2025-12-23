<div class="modal fade" id="partialDispatchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <form id="createFormSubmit" class="form-horizontal m-t-10" method="POST" action="{{ route('partialdispatch') }}">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update Quotation Status</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
           <table class="table table-bordered" id="partialDispatchTable">
    <thead>

        <tr>
            <th>Quotation No</th>
            <th>Product Name</th>
            <th>Quotation Qty</th>
            <th>Dispatched Qty</th>
            <th>Dispatch Qty</th>
        </tr>
    </thead>

    <tbody id="partialDispatchBody">
        <!-- JS Will Insert Rows Here -->
    </tbody>
</table>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>
    </form>
  </div>
</div>
