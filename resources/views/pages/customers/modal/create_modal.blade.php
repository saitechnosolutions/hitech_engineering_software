<div class="modal fade" id="createCustomerModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="createFormSubmit" class="form-horizontal m-t-10" method="POST" action="{{ route('customers.store') }}">
        @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Customers</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                                        <label>Customer Type</label>
                                        <div>
                                            <select class="form-control" name="customer_type">
                                                <option value="">-- Choose Option --</option>
                                                <option value="against_delivery">Against Delivery</option>
                                                <option value="after_delivery">After Delivery</option>
                                                <option value="full_advance_customer">Full Advance Customer</option>
                                                <option value="part_advance_customer">Part Advance Customer</option>
                                            </select>
                                        </div>
                                    </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                                        <label>Customer Name</label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="customer" placeholder=""/>
                                        </div>
                                    </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                                        <label>E-mail</label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="email" placeholder=""/>
                                        </div>
                                    </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                                        <label>Mobile Number</label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="mobile_number" placeholder=""/>
                                        </div>
                                    </div>
            </div>
            <div class="col-lg-6">
                 <div class="form-group">
                                        <label>GST Number</label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="gst_number" placeholder=""/>
                                        </div>
                                    </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                                        <label>Pincode</label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="pincode" placeholder=""/>
                                        </div>
                                    </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                                        <label>Address</label>
                                        <div>
                                            <textarea class="form-control" name="address"></textarea>
                                        </div>
                                    </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                                        <label>State</label>
                                        <div>
                                            <select class="form-control" name="state_id">
                                                <option value="">-- Choose State --</option>
                                                @if($states = App\Models\State::get())
                                                    @foreach($states as $state)
                                                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                             
                                        </div>
                                    </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                                        <label>Wholesale Price</label>
                                        <div>
                                             <input type="text" class="form-control" required
                                                    parsley-type="text" name="wholesale_price" placeholder=""/>
                                        </div>
                                    </div>
            </div>

            <div class="col-lg-6">
                 <div class="form-group">
                                        <label>Discount</label>
                                        <div>
                                             <input type="text" class="form-control" required
                                                    parsley-type="text" name="discount" placeholder=""/>
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
