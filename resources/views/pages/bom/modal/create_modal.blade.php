<div class="modal fade" id="createBomModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <form id="createFormSubmit" class="form-horizontal m-t-10" method="POST" action="{{ route('bom.store') }}">
        @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">BOM</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                                        <label>Product Name</label>
                                        <div>
                                            <select class="form-control js-example-basic-single" name="product_id" style="width:450px">
                                                <option value="">-- Choose Option --</option>
                                                @if($products = App\Models\Product::get())
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
            </div>
        </div>
        <div class="row">

            <table class="table table-bordered" id="allStageTableRow">
                <thead>
                    <th>BOM Part Name</th>
                    <th>Unit</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Stages</th>
                    <th>Add</th>
                </thead>
                <tbody >
                    <tr>
                        <td>
                            <input type="text" class="form-control" required parsley-type="text" name="bom_part_name" placeholder=""/>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="bom_unit" required parsley-type="text" placeholder=""/>
                        </td>
                        <td>
                             <input type="text" class="form-control" name="quantity" required
                                                    parsley-type="text" placeholder=""/>
                        </td>
                        <td>
                             <input type="text" class="form-control" name="price" required
                                                    parsley-type="text" placeholder=""/>
                        </td>
                        <td>
                            <table class="table table-bordered" id="stagesTable">
                                <thead>
                                    <tr>
                                        <th>Stages</th>
                                        <th><button type="button" class="btn btn-success" id="addRow">+</button></th>
                                    </tr>
                                </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="form-group m-0">
                                                    <div>
                                                        <select class="form-control" name="process_team[]">
                                                            <option value="">-- Choose Option --</option>
                                                            @if($processTeams = App\Models\ProcessTeam::get())
                                                                @foreach ($processTeams as $processTeam)
                                                                    <option value="{{ $processTeam->id }}">{{ $processTeam->team_name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger removeRow">-</button>
                                            </td>
                                        </tr>
                                    </tbody>
                            </table>
                            </td>
                            <td>
                                <button type="button" class="btn btn-success" id="addRowFullTable">+</button>
                            </td>
                    </tr>
                </tbody>
            </table>

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
