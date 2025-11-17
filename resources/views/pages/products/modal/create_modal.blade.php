<div class="modal fade" id="createCustomerModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="createFormSubmit" class="form-horizontal m-t-10" method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data"">
        @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Products</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                                        <label>Category Name</label>
                                        <div>
                                            <select class="form-control" name="category_id">
                                                <option value="">-- Choose Category --</option>
                                                @if($categories = App\Models\Category::get())
                                                @foreach ($categories as $categorie)
                                                 <option value="{{ $categorie->id }}">{{ $categorie->name }}</option>
                                                @endforeach
                                            @endif
                                            </select>
                                        </div>
                                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                                        <label>Product Name</label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="product_name" placeholder=""/>
                                        </div>
                                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                                        <label>Product Image</label>
                                        <div>
                                            <input type="file" class="form-control" required
                                                    parsley-type="text" name="product_image" placeholder=""/>
                                        </div>
                                    </div>
                </div>

                <div class="col-lg-6">
                     <div class="form-group">
                                        <label>Brand</label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="brand" placeholder=""/>
                                        </div>
                                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                                        <label>Bike Model</label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="bike_model" placeholder=""/>
                                        </div>
                                    </div>
                </div>
                 <div class="col-lg-6">
                    <div class="form-group">
                                        <label>MRP Price</label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="mrp_price" placeholder=""/>
                                        </div>
                                    </div>
                 </div>
                 <div class="col-lg-6">
                     <div class="form-group">
                                        <label>Part Number</label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="part_number" placeholder=""/>
                                        </div>
                                    </div>
                 </div>
                 <div class="col-lg-6">
                     <div class="form-group">
                                        <label>Quantity</label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="quantity" placeholder=""/>
                                        </div>
                                    </div>
                 </div>
                 <div class="col-lg-6">
                     <div class="form-group">
                                        <label>Variation</label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="variation" placeholder=""/>
                                        </div>
                                    </div>
                 </div>
                 <div class="col-lg-6">
                    <div class="form-group">
                                        <label>HSN/SAC Code</label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="hsn_code" placeholder=""/>
                                        </div>
                                    </div>
                 </div>

                 <div class="col-lg-6">
                     <div class="form-group">
                                        <label>Stock Qty</label>
                                        <div>
                                            <input type="text" class="form-control" required
                                                    parsley-type="text" name="stock_qty" placeholder=""/>
                                        </div>
                                    </div>
                 </div>
                 <div class="col-lg-6">
                    <div class="form-group">
                                        <label>Design Sheet</label>
                                        <div>
                                            <input type="file" class="form-control" required
                                                    parsley-type="text" name="design_sheet" placeholder=""/>
                                        </div>
                                    </div>
                </div>
                 <div class="col-lg-6">
                    <div class="form-group">
                                        <label>Data Sheet</label>
                                        <div>
                                            <input type="file" class="form-control" required
                                                    parsley-type="text" name="data_sheet" placeholder=""/>
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
