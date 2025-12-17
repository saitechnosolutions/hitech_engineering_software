<div class="modal fade" id="dispatchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">

                    @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Dispatch type</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <img src="/assets/images/dispatch-image.jpg" class="img-fluid" style="margin:0 auto;display:block">
        <div class="text-center d-flex justify-content-center">
            <button class="btn btn-danger updateStockOnProduct mr-3" data-quotationid="{{ $quotations }}"><i class="fa fa-building" aria-hidden="true"></i> Hitech Dispatch</button>
            <div class="dropdown">
                <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-users" aria-hidden="true"></i> Customer Dispatch
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item productDispatched"  ><i class="fa fa-paper-plane" aria-hidden="true"></i> &nbsp;&nbsp; Full Dispatch</a>

                    <a class="dropdown-item" href="#">Partial Dispatch</a>
                </div>
            </div>
        </div>


      </div>

    </div>

  </div>
</div>
