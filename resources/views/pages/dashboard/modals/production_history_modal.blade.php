<div class="modal fade" id="productionHistoryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="createFormSubmit" class="form-horizontal m-t-10" method="POST" action="{{ route('production.updateStatus') }}">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update Production Status</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">


           <table class="table table-bordered" id="productionHistoryTable">
    <thead>
        <tr>
            <th colspan="3">
                <span id="productName" style="color:red"></span>
                <span id="orderedQty"></span>
            </th>
        </tr>
        <tr>
            <th>BOM Name</th>
            <th>BOM Qty</th>
            <th>Received Qty</th>
        </tr>
    </thead>

    <tbody id="productionHistoryBody">
        <!-- JS Will Insert Rows Here -->
    </tbody>
</table>

            <input type="hidden" name="product_id" id="product_id">
            <input type="hidden" name="quotationid" id="quotationid">
            <input type="hidden" name="productionType" id="productionType">
            <input type="hidden" name="team" id="team">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>
    </form>
  </div>
</div>
