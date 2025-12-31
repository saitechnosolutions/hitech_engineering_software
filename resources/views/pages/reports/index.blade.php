@extends('layouts.app')
@section('main-content')
<x-breadcrumb
    :items="[
        ['label' => 'Hi-tech Engineering', 'url' => '#'],
        ['label' => 'Pages', 'url' => '#'],
        ['label' => 'Users']
    ]"
    title="" />

 <div class="row mt-3">
        <div class="col-lg-3">
            <a href="/collection-report">
            <div class="card text-center" style="height:150px;">
                <div class="card-body">
                    <span style="font-size:45px;color:#1e4c6b"><i class="fa fa-line-chart" aria-hidden="true"></i></span>
                    <h6 style="color:#ec1c24">Collection Reports</h6>
                </div>
            </div>
            </a>
        </div>
        <div class="col-lg-3">
            <a href="/product-stock-report">
            <div class="card text-center" style="height:150px;">
                <div class="card-body">
                    <span style="font-size:45px;color:#1e4c6b"><i class="fa fa-pie-chart" aria-hidden="true"></i></span>
                    <h6 style="color:#ec1c24">Product Stock Reports</h6>
                </div>
            </div>
            </a>
        </div>


<div class="col-lg-3">
    <a href="/component-stock-report">
            <div class="card text-center" style="height:150px;">
                <div class="card-body">
                    <span style="font-size:45px;color:#1e4c6b"><i class="fa fa-pie-chart" aria-hidden="true"></i></span>
                    <h6 style="color:#ec1c24">Component Stock Reports</h6>
                </div>
            </div>
    </a>
        </div>

        <div class="col-lg-3">
            <a href="/employee-wise-production-report">
                <div class="card text-center" style="height:150px;">
                <div class="card-body">
                    <span style="font-size:45px;color:#1e4c6b"><i class="fa fa-area-chart" aria-hidden="true"></i></span>
                    <h6 style="color:#ec1c24">Employee Wise Production Report</h6>
                </div>
            </div>
            </a>

        </div>
        <div class="col-lg-3 mt-3">
            <a href="/sales-stock-report">
            <div class="card text-center" style="height:150px;">
                <div class="card-body">
                    <span style="font-size:45px;color:#1e4c6b"><i class="fa fa-bar-chart" aria-hidden="true"></i></span>
                    <h6 style="color:#ec1c24">Sales Reports</h6>
                </div>
            </div>
            </a>
        </div>


            <div class="col-lg-3 mt-3">
                 <a href="/task-report">
                <div class="card text-center" style="height:150px;">
                    <div class="card-body">
                        <span style="font-size:45px;color:#1e4c6b"><i class="fa fa-tasks" aria-hidden="true"></i></span>
                        <h6 style="color:#ec1c24">Task Reports</h6>
                    </div>
                </div>
                   </a>
            </div>


         <div class="col-lg-3 mt-3">
             <a href="/quotation-report">
            <div class="card text-center" style="height:150px;">
                <div class="card-body">
                    <span style="font-size:45px;color:#1e4c6b"><i class="fa fa-file-text" aria-hidden="true"></i></span>
                    <h6 style="color:#ec1c24">Quotation Reports</h6>
                </div>
            </div>
             </a>
        </div>

        <div class="col-lg-3 mt-3">
             <a href="/bom-purchase-report">
            <div class="card text-center" style="height:150px;">
                <div class="card-body">
                    <span style="font-size:45px;color:#1e4c6b"><i class="fa fa-file-o" aria-hidden="true"></i></span>
                    <h6 style="color:#ec1c24">BOM Purchase Report</h6>
                </div>
            </div>
             </a>
        </div>

        <div class="col-lg-3 mt-3">
             <a href="/stock-in-out-report">
            <div class="card text-center" style="height:150px;">
                <div class="card-body">
                    <span style="font-size:45px;color:#1e4c6b"><i class="fa fa-file-o" aria-hidden="true"></i></span>
                    <h6 style="color:#ec1c24">Stock In & Out Report</h6>
                </div>
            </div>
             </a>
        </div>
 </div>
@endsection



