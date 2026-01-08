<div class="row">
    <div class="col-lg-12">
        <div class="d-flex dash_over" style="justify-content:space-between">

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


            <div class="d-flex flex-row  p-3" style="width:283px;background-color:#f94966">
                <div class="col-12 text-center align-self-center">
                    <div class="m-l-10 ">
                        <h5 class="mt-0 round-inner text-white">{{ $inProductionCount ?? 0 }}</h5>
                        <p class="mb-0 text-white">Inproduction</p>
                    </div>
                </div>

            </div>

            <a href="/payment-collection-today">
                <div class="d-flex flex-row  p-3" style="width:283px;background-color:#069a6a">
                    <div class="col-12 text-center align-self-center">
                        <div class="m-l-10 ">
                            <h5 class="mt-0 round-inner text-white">
                                ₹{{ number_format($todayPaymentColledtedCount->sum('amount'), 2) }}
                                ({{ $todayPaymentColledtedCount->count() ?? 0 }})</h5>
                            <p class="mb-0  text-white">Payment Collected (Today)</p>
                        </div>
                    </div>

                </div>
            </a>

            <a href="/collection-pending">
                <div class="d-flex flex-row p-3" style="width:283px;background-color:#896C6C">
                    <div class="col-12 text-center align-self-center">
                        <div class="m-l-10 ">
                            <h5 class="mt-0 round-inner text-white">
                                ₹{{ number_format($collectionPendingAmount, 2) ?? 0.00 }}</h5>
                            <p class="mb-0  text-white">Collection Pending (Total)</p>
                        </div>
                    </div>

                </div>
            </a>

            <a href="/revenue-details">

                <div class="d-flex flex-row  p-3" style="width:283px;background-color:#f76d30">
                    <div class="col-12 text-center align-self-center">
                        <div class="m-l-10 ">
                            <h5 class="mt-0 round-inner text-white">₹{{ number_format($revenueAmount, 2) ?? 0.00 }}</h5>
                            <p class="mb-0 text-white">Revenue</p>
                        </div>
                    </div>

                </div>
            </a>
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-lg-7 pr-0">
        <div class="card bg-white" style="height:460px">
            <div class="card-header bg-white">
                Order Status (Customer Wise)
            </div>


            <div class="card-body">
                {{--  <form class="orderstatusSubmission" action="{{ route('orderstatusReportFilter') }}"
                    id="orderstatusReportFilter">
                    @csrf
                    <div class="row">

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Company Name </label>

                                @php
                                    $customers = App\Models\Customer::all();
                                @endphp
                                <select class="form-control" name="company_name" id="companyName"
                                    class="form-control js-example-basic-single" style="">
                                    <option value="">-- Choose Customer --</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->customer_name }}">{{ $customer->customer_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>From Date</label>
                                <input type="date" class="form-control" name="fromdate" id="fromDate">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Todate</label>
                                <input type="date" class="form-control" name="todate" id="toDate">
                            </div>
                        </div>

                        <div class="col-lg-3 mb-4">
                            <button type="submit" class="btn btn-danger mt-4">Filter</button>
                        </div>

                    </div>
                </form>  --}}
                <table class="table table-bordered" style="border:1px solid #eee" >
                    <thead style="background-color:#f1f5f9">
                        <tr>
                            <th style="padding:6px">Order ID</th>
                            <th style="padding:6px">Customer</th>
                            <th style="padding:6px">Status</th>
                            <th style="padding:6px">Delivery</th>
                            <th style="padding:6px">Value</th>
                            <th style="padding:6px">Payments</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($orderDetails->count() > 0)
                            @foreach ($orderDetails as $orderDetail)
                                <tr>
                                    <td style="padding:6px">

                                        <a href="" style="color:#ec1c24;font-weight:bold">{{ $orderDetail?->quotation_no }}</a>
                                    </td>
                                    <td style="padding:6px">{{ $orderDetail?->customer?->customer_name }}</td>
                                    <td style="padding:6px;text-align:center"><span class="badge bg-secondary text-white"
                                            style="width:150px">{{ removeUnderscoreText($orderDetail->production_status) }}</span>
                                    </td>
                                    @if($orderDetail->production_status == 'completed')
                                        <td style="padding:6px;text-align:center;color:white"><span
                                                class="badge bg-success">Diapatched</span></td>
                                    @else
                                        <td style="padding:6px;text-align:center"><span class="badge bg-warning">Onprocess</span>
                                        </td>
                                    @endif

                                    <td style="padding:6px">₹{{ number_format($orderDetail->total_collectable_amount, 2) }}</td>
                                    <td style="padding:6px">
                                        ₹{{ number_format($orderDetail->payments->sum('amount'), 2) ?? 0.00 }}</td>
                                </tr>
                            @endforeach

                            @else
                            <tr>
                                <td colspan="6">
                                    <img src="/assets/no-data.png" class="img-fluid m-auto d-block" style="width:500px">
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>


        </div>
    </div>

    <div class="col-lg-5">
        <div class="card bg-white p-0" style="height:460px">
            <div class="card-header bg-white">
                Weekly Revenue Analytics
            </div>
            <div class="card-body ">
                <div style="height: 460px;margin-top:30px">
                    <canvas style="height:460px" id="twoWeekChart" data-lastweek='@json($lastWeekData)'
                        data-currentweek='@json($currentWeekData)'>
                    </canvas>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-lg-9 pr-0">
        <div class="card">
            <div class="card-header bg-white">Quote Completed</div>
            <div class="card-body" style="height:460px">
                {{--  <form class="QuotestatusSubmission" action="{{ route('QuotestatusReportFilter') }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group">
                                <label>Company Name</label>
                                @php $customers = App\Models\Customer::all(); @endphp
                                <select class="form-control" name="company_name" id="companyName"
                                    class="form-control js-example-basic-single">
                                    <option value="">-- Choose Customer --</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->customer_name }}">{{ $customer->customer_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6">
                            <div class="form-group">
                                <label>From Date</label>
                                <input type="date" class="form-control" name="fromdate" id="fromDate">
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6">
                            <div class="form-group">
                                <label>To Date</label>
                                <input type="date" class="form-control" name="todate" id="toDate">
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 mb-4">
                            <button type="submit" class="btn btn-danger mt-4">Filter</button>
                        </div>
                    </div>
                </form>  --}}

                <table class="table table-bordered" >
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Quotation No</th>
                            <th>Customer Name</th>
                            <th>Status</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($completedQuotations->count() > 0)
                            @foreach ($completedQuotations as $completedQuotation)
                                <tr>
                                    <td>{{ $completedQuotation?->quotation_date }}</td>
                                    <td>
                                        <a href="#"
                                            style="color:red;font-weight:bold">{{ $completedQuotation->quotation_no }}</a>
                                    </td>
                                    <td>{{ $completedQuotation?->customer?->customer_name }}</td>
                                    <td class="text-center"><span class="badge bg-success text-white">Completed</span></td>
                                    <td>₹{{ number_format($completedQuotation->total_collectable_amount, 2) }}</td>
                                </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="5">
                                    <img src="/assets/no-data.png" class="img-fluid m-auto d-block" style="width:500px">
                                </td>
                            </tr>
                        @endif

                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div class="col-lg-3">
        <div class="card">
            <div class="card-header">
                Team Workload
            </div>
            <div class="card-body p-0" style="height:460px">
                <ul class="list-group">
                    @if($processTeams)
                        @foreach ($processTeams as $processTeam)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $processTeam->team_name }}

                                <span class="badge badge-primary badge-pill">
                                    {{ $processTeam->quotationProductionStages->where('status', 'pending')->groupBy('quotation_id')->count() }}

                                </span>
                            </li>
                        @endforeach
                    @endif
                </ul>

            </div>
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-lg-9 pr-0">
        <div class="card">
            <div class="card-header bg-white">
                Components
            </div>
            <div class="card-body" style="height:640px">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="padding:6px">Component Name</th>
                            <th style="padding:6px">Code</th>
                            <th style="padding:6px">Overall Stock</th>
                            <th style="padding:6px">Used Stock</th>
                            <th style="padding:6px">Available Stock</th>
                            <th style="padding:6px">Unit Price</th>
                            <th style="padding:6px">Status</th>

                        </tr>
                    </thead>

                    <tbody>
                        @if($components = App\Models\ProductComponents::with('productionQuantity')->get()->take(10))

                            @foreach ($components as $component)
                                @php
                                    $availableStock = $component->stock_qty - $component->productionQuantity->sum('bom_qty');

                                    $status = '';
                                    if ($availableStock < 10) {
                                        $status = '<span class="badge bg-danger text-white">Low Quantity</span>';
                                    } else {
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

                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="card">
            <div class="card-header">
                Tasks
            </div>
            <div class="card-body p-0" style="height:460px">

                <ul class="list-group">
                    @if($recentTasks)
                        @foreach ($recentTasks as $recentTask)

                            <li class="list-group-item">
                                @if($recentTask->status == 'pending')
                                    <i class="fa fa-tasks"
                                        style="background-color:#ff7b90;padding:13px;border-radius:50px;color:white"
                                        aria-hidden="true"></i>
                                @else
                                    <i class="fa fa-check"
                                        style="background-color:#80e0c1;padding:13px;border-radius:50px;color:#ffffff"
                                        aria-hidden="true"></i>
                                @endif

                                &nbsp;&nbsp;{{ $recentTask->title }}
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
