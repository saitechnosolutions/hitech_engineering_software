<div class="modal fade" id="createComponentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="createFormSubmit" class="form-horizontal m-t-10" method="POST" action="{{ route('components.store') }}" enctype="multipart/form-data">
        @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Product Components</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <div class="row">

                <div class="col-lg-6">
                    <div class="form-group">
                                        <label>Component Name</label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="component_name" placeholder=""/>
                                        </div>
                                    </div>
                </div>



                <div class="col-lg-6">
                     <div class="form-group">
                                        <label>Stock Quantity</label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="stock_qty" placeholder=""/>
                                        </div>
                                    </div>
                </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                                        <label>Code</label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="code" placeholder=""/>
                                        </div>
                                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                                        <label>Unit Price</label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="unit_price" placeholder=""/>
                                        </div>
                                    </div>
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
