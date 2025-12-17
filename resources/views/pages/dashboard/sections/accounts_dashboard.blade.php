<div class="row">
    <div class="col-lg-12">
        <div class="d-flex" style="justify-content:space-between">
                            <a href="/active-order-details">
                            <div class="d-flex  flex-row p-3" style="width:283px;background-color:#0967e9">
                                    <div class="col-12 text-center align-self-center ">
                                        <div class="m-l-10 ">
                                            <h5 class="mt-0 round-inner text-white">{{ $activeOrdersCount ?? 0 }}</h5>
                                            <p class="mb-0 text-white">Active Orders</p>
                                        </div>
                                    </div>

                                </div>
                            </a>

                            <a href="/collection-pending">
                                <div class="d-flex flex-row  p-3" style="width:283px;background-color:#f94966">
                                    <div class="col-12 text-center align-self-center">
                                        <div class="m-l-10 ">
                                            <h5 class="mt-0 round-inner text-white">₹{{ number_format($collectionPendingAmount, 2) ?? 0.00 }}</h5>
                                            <p class="mb-0 text-white">Payment Pending</p>
                                        </div>
                                    </div>

                                </div>
                            </a>

                            <a href="/retail-active-orders">
                                 <div class="d-flex flex-row  p-3" style="width:283px;background-color:#069a6a">
                                    <div class="col-12 text-center align-self-center">
                                        <div class="m-l-10 ">
                                            <h5 class="mt-0 round-inner text-white">{{ $retailOrdersCount ?? 0 }}</h5>
                                            <p class="mb-0 text-white">Retail Active Order</p>
                                        </div>
                                    </div>

                                </div>
                            </a>

                            <a href="/retail-completed-orders">
                                <div class="d-flex flex-row p-3" style="width:283px;background-color:#896C6C">
                                    <div class="col-12 text-center align-self-center">
                                        <div class="m-l-10 ">
                                            <h5 class="mt-0 round-inner text-white">{{ $retailOrdersCountCompleted ?? 0 }}</h5>
                                            <p class="mb-0 text-white">Retail Completed</p>
                                        </div>
                                    </div>
                                </div>
                            </a>

                                <a href="/invoice-request-details">
                                    <div class="d-flex flex-row  p-3" style="width:283px;background-color:#f76d30">
                                    <div class="col-12 text-center align-self-center">
                                        <div class="m-l-10 ">
                                            <h5 class="mt-0 round-inner text-white">{{ $invoiceRequests->count(); }}</h5>
                                            <p class="mb-0 text-white">Invoice Requested</p>
                                        </div>
                                    </div>
                                </div>
                                </a>

        </div>
    </div>
 </div>

 <div class="row mt-2">
    <div class="col-lg-12 pr-0">
        <div class="card">
            <div class="card-header bg-white">
                Components Stock
            </div>
            <div class="card-body" style="height:720px">
                <table class="table table-bordered" id="dataTable_two">
                    <thead>
                        <tr>
                            <th style="padding:6px">Component Name</th>
                            <th style="padding:6px">Code</th>
                            <th style="padding:6px">Overall Stock</th>
                            <th style="padding:6px">Used Stock</th>
                            <th style="padding:6px">Available Stock</th>
                            <th style="padding:6px">Unit Price</th>
                            <th style="padding:6px">Status</th>
                            <th style="padding:6px">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if($components = App\Models\ProductComponents::with('productionQuantity')->get()->take(10))

                            @foreach ($components as $component)
                             @php
                                $availableStock = $component->stock_qty - $component->productionQuantity->sum('bom_qty');

                                $status = '';
                                if($availableStock < 10)
                                {
                                    $status = '<span class="badge bg-danger text-white">Low Quantity</span>';
                                }
                                else
                                    {
                                        $status = '<span class="badge bg-success text-white">Available</span>';
                                    }
                            @endphp
                                <tr>
                                    <td>{{ $component->component_name }}</td>
                                    <td>{{ $component->code }}</td>
                                    <td>{{ $component->stock_qty }}</td>
                                    <td>{{ $component->productionQuantity->sum('bom_qty') }}</td>
                                    <td>{{ $availableStock }}</td>
                                    <td>₹{{ number_format($component->unit_price, 2) }}</td>
                                    <td>{!! $status !!}</td>
                                    <td>{{ $component->stock_qty }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
