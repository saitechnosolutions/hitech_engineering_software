<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="createFormSubmit" class="form-horizontal m-t-10" method="POST" action="{{ route('categories.store') }}" data-datatable-id="category-table">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Categories</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

                                      <div class="form-group">
                                        <label>Category Name</label>
                                        <div>
                                            <input type="text" class="form-control" name="name" required
                                                    parsley-type="text" placeholder=""/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Category Code</label>
                                        <div>
                                            <input type="text" class="form-control" name="category_code" required
                                                    parsley-type="text" placeholder=""/>
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
