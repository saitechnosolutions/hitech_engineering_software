<html>
    <head>
        <title>Quotation</title>
        <style>
        @page {
            size: A4;
            margin: 50px;
        }

    body {
        font-family: Helvetica;
        font-size: 12px;
        margin: 0;
        padding: 0;
    }


    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        word-wrap: break-word;
    }

    th, td {
        border: 1px solid #000;
        padding: 3px;
        text-align: left;
        font-size: 10px;
    }

    th {
        background-color: #f2f2f2;
    }

    h1, h2, p {
        margin: 5px 0;
    }

</style>

    </head>
    <body>
        <table style="border-collapse:collapse;width:100%">
            <thead>
                <tr>

                    <td colspan="11" style="text-align: center;font-size:18px;border:none"><b>QUOTATION</b></td>
                </tr>
                <tr>
                    <td colspan="4" rowspan="3" style="padding:2px">
                        <b>HI-TECH ENGINEERING</b><br>
                        No: 2/109, Nattamangalam Main Road, Maniyanur,
                        Near Vinayagar Temple, <br>Salem - 636010.<br>
                        GSTIN/UIN: 33APYPG7263A1ZU<br>
                        State Name :  Tamil Nadu, Code : 33<br>
                        Contact : 6383745690, 9488715230<br>
                        E-Mail : support@htexhaust.com
                    </td>
                    <td colspan="4">
                       Quotation No : <br><b>{{ $quotation->quotation_no }}</b>
                    </td>
                    <td colspan="3"> Dated : <br><b>{{ formatDate($quotation->quotation_date) }}</b></td>
                </tr>
                 <tr>
                    <td colspan="4">

                    </td>
                    <td colspan="3"> Mode/Terms of Payment : <br>{{ $quotation->mode_terms_of_payment }}</td>
                </tr>
                <tr>
                    <td colspan="4">
                       <b>Buyer's Ref./Order No :</b> <br> {{ $quotation->other_references }}
                    </td>
                    <td colspan="3"> <b>  Other References :</b> <br>{{ $quotation->dispatch_through }}</td>
                </tr>
                <tr>
                    <td colspan="4" >
                      CONSIGNEE (Ship to)<br>
                      <b style="text-transform: uppercase">{{ $quotation->customer->customer_name }}</b><br>
                        {!! nl2br($quotation->customer->address) !!}<br>
                        GST Number : {{ $quotation->customer->gst_number }}<br>
                        State : {{ $quotation->customer->state }}<br>
                        Contact Number : {{ $quotation->customer->mobile_number }}<br>
                    </td>
                    <td colspan="4"  style="vertical-align: top">
                       <b>Dispatched through :</b> <br>
                    </td>
                    <td colspan="3"  style="vertical-align: top"> <b>Destination :</b> <br></td>
                </tr>
                 <tr>
                    <td colspan="4" >
                      BUYER (Bill to)<br>
                      <b style="text-transform: uppercase">{{ $quotation->customer->customer_name }}</b><br>
                        {!! nl2br($quotation->customer->address) !!}<br>
                        GST Number : {{ $quotation->customer->gst_number }}<br>
                        State : {{ $quotation->customer->state }}<br>
                        Contact Number : {{ $quotation->customer->mobile_number }}<br>
                    </td>
                    <td colspan="7"  style="vertical-align: top">
                       <b>Terms Of Delivery :</b> <br>
                    </td>

                </tr>

                  <tr>
                    <th style="font-size:10px;text-align:center;">SL<br>No</th>
                    <th colspan="3" style="font-size:10px;text-align:center">Description of Goods</th>
                    <th style="font-size:10px;text-align:center">HSN/SAC</th>
                    <th style="font-size:10px;text-align:center">Part No</th>
                    <th style="font-size:10px;text-align:center">Quantity</th>
                    <th style="font-size:10px;text-align:center">Rate</th>
                    <th style="font-size:10px;text-align:center">Per</th>
                    <th style="font-size:10px;text-align:center">Disc. %</th>
                    <th style="font-size:10px;text-align:center">Amount</th>
                </tr>
            </thead>
            <tbody>
               @php
    $i = 1;
    $products = $quotation->quotationProducts;
    $totalProducts = $products->count();

    $firstPageCount = 16; // first page shows max 15 products
    $firstPageProducts = $products->slice(0, $firstPageCount);
    $remainingProducts = $products->slice($firstPageCount);

    $emptyRows = $firstPageCount - $firstPageProducts->count(); // blank rows to fill first page
@endphp

@if($products)
    {{-- FIRST PAGE --}}
    @foreach ($firstPageProducts as $product)
        <tr>
            <td style="text-align:center;border-bottom:none;border-top:none">{{ $i++ }}</td>
            <td style="border-bottom:none;border-top:none" colspan="3"><b>{{ $product->product->product_name }}</b></td>
            <td style="text-align:center;border-bottom:none;border-top:none">{{ $product->product->hsn_code }}</td>
            <td style="text-align:center;border-bottom:none;border-top:none">{{ $product->product->part_number }}</td>
            <td style="text-align:center;border-bottom:none;border-top:none">{{ number_format($product->quantity, 3) }}</td>
            <td style="text-align:right;border-bottom:none;border-top:none">{{ $product->rate }}</td>
            <td style="text-align:center;border-bottom:none;border-top:none">Nos</td>
            <td style="text-align:center;border-bottom:none;border-top:none">{{ $product->discount_percentage }}%</td>
            <td style="text-align:right;border-bottom:none;border-top:none">{{ $product->total_amount }}</td>
        </tr>
    @endforeach

    {{-- Blank filler rows if less than 15 --}}
    @for($j=0; $j < $emptyRows; $j++)
        <tr>
            <td style="height:25px;text-align:center;border-bottom:none;border-top:none"></td>
            <td style="border-bottom:none;border-top:none" colspan="3"></td>
            <td style="border-bottom:none;border-top:none"></td>
            <td style="border-bottom:none;border-top:none"></td>
            <td style="border-bottom:none;border-top:none"></td>
            <td style="border-bottom:none;border-top:none"></td>
            <td style="border-bottom:none;border-top:none"></td>
            <td style="border-bottom:none;border-top:none"></td>
            <td style="border-bottom:none;border-top:none"></td>
        </tr>
    @endfor

    {{-- If there are remaining products, start second page --}}
    @if($remainingProducts->count() > 0)
        <tr>
            <td style="text-align:center;border:1px solid black" colspan="11">
                Page 2
            </td>
        </tr>
        <tr style="page-break-after: always;">
            <td style="border:none" colspan="11"></td>
        </tr>

        @foreach($remainingProducts as $product)
            <tr>
                <td style="text-align:center;border-bottom:none;border-top:none">{{ $i++ }}</td>
                <td style="border-bottom:none;border-top:none" colspan="3"><b>{{ $product->product->product_name }}</b></td>
                <td style="text-align:center;border-bottom:none;border-top:none">{{ $product->product->hsn_code }}</td>
                <td style="text-align:center;border-bottom:none;border-top:none">{{ $product->product->part_number }}</td>
                <td style="text-align:center;border-bottom:none;border-top:none">{{ number_format($product->quantity, 3) }}</td>
                <td style="text-align:right;border-bottom:none;border-top:none">{{ $product->rate }}</td>
                <td style="text-align:center;border-bottom:none;border-top:none">Nos</td>
                <td style="text-align:center;border-bottom:none;border-top:none">{{ $product->discount_percentage }}%</td>
                <td style="text-align:right;border-bottom:none;border-top:none">{{ $product->total_amount }}</td>
            </tr>
        @endforeach
    @endif

    {{-- Totals, Bank Details, Signatures (only on last page) --}}
    <tr>
        <td style="border-bottom:0px;border-top:0px"></td>
        <td colspan="3" style="font-weight:bold;text-align:right;border-bottom:0px;border-top:0px"></td>
        <td style="border-bottom:0px;border-top:0px"></td>
        <td style="border-bottom:0px;border-top:0px"></td>
        <td style="border-bottom:0px;border-top:0px"></td>
        <td style="border-bottom:0px;border-top:0px"></td>
        <td style="border-bottom:0px;border-top:0px"></td>
        <td style="border-bottom:0px;border-top:0px"></td>
        <td style="text-align:right;border-bottom:0px;border-top:1px solid black">{{ $quotation->quotationProducts->sum('total_amount') }}</td>
    </tr>
    <tr>
        <td style="border-bottom:0px;border-top:0px"></td>
        <td colspan="3" style="border-bottom:0px;border-top:0px;font-weight:bold;text-align:right">IGST 18%</td>
        <td style="border-bottom:0px;border-top:0px"></td>
        <td style="border-bottom:0px;border-top:0px"></td>
        <td style="font-weight:bold;text-align:center;border-bottom:0px;border-top:0px"></td>
        <td style="border-bottom:0px;border-top:0px;text-align:right">18</td>
        <td style="border-bottom:0px;border-top:0px;text-align:left">%</td>
        <td style="border-bottom:0px;border-top:0px"></td>
        <td style="text-align:right;font-weight:bold;border-bottom:0px;border-top:0px">{{ $quotation->quotationProducts->sum('total_amount') / 100 * 18 }}</td>
    </tr>
    <tr>
        <td></td>
        <td colspan="3" style="font-weight:bold;text-align:right">TOTAL</td>
        <td></td>
        <td></td>
        <td style="font-weight:bold;text-align:center">{{ number_format($quotation->quotationProducts->sum('quantity'), 3) }} </td>
        <td></td>
        <td></td>
        <td></td>
        <td style="font-weight:bold;text-align:right">{{ $quotation->quotationProducts->sum('total_amount') + $quotation->quotationProducts->sum('total_amount') / 100 * 18 }}</td>
    </tr>
    <tr>
        <td colspan="4">Amount Chargeable (in words) <br>
            <span> INR Fifteen Thousand Five Hundred Seventeen Only</span>
        </td>
        <td colspan="7">
            <span>Company's Bank Details</span><br>
            <span>Bank Name : AXIS BANK</span><br>
            <span>A/c No. : 924020034513197</span><br>
            <span>Branch & IFS Code : Gugai SALEM & UTIB0001109</span>
        </td>
    </tr>
    <tr>
        <td style="height:30px" colspan="4"></td>
        <td colspan="7" style="width:100%;">
            <div style="display: flex; justify-content: space-between; text-align: center; width: 100%;">
                <span>Prepared by</span>
                <span>Verified by</span>
                <span>Authorised Signatory</span>
            </div>
        </td>
    </tr>
    <tr>
        <td style="text-align:center;border:none" colspan="11"> This is a Computer Generated Document</td>
    </tr>
@endif

            </tbody>
        </table>
    </body>
</html>


