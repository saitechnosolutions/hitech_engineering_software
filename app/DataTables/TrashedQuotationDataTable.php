<?php

namespace App\DataTables;

use App\Models\Quotation;
use App\Models\PaymentDetails;
use App\Models\TrashedQuotation;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class TrashedQuotationDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<TrashedQuotation> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function($query){
                return "
                    <div class='dropdown'>
            <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                <i class='fa fa-cog' aria-hidden='true'></i>
            </button>
            <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                <a class='dropdown-item removeTrash' data-quotationid='{$query->id}'>Remove Trash</a>
            </div>
            </div>
                ";
})
->addColumn('download', function($query){
    return "<a href='/quotation_format/{$query->id}'>Download</a>";
})
->addColumn('customer_id', function($query){
    return $query->customer->customer_name;
})
->addColumn('production_status', function($query){
    return removeUnderscoreText($query->production_status);
})
->addColumn('payment_details', function($query){

    $paymentDetails = PaymentDetails::where('quotation_id', $query->id)->get();

    $html = '<table class="table table-bordered">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Payment Date</th>
                        <th>Paid Amount</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>';

    if($paymentDetails->count()){
        $i = 1;
        foreach($paymentDetails as $payment){
            $html .= '<tr>
                        <td>'.$i++.'</td>
                        <td>'.($payment->payment_date ?? '-').'</td>
                        <td>'.$payment->amount.'</td>
                        <td>'.$payment->remarks.'</td>
                      </tr>';
        }
    } else {
        $html .= '<tr>
                    <td colspan="4" style="text-align:center">No Payments</td>
                  </tr>';
    }

    $html .= '</tbody></table>';

    return $html;
})

->addColumn('lr_details', function($query){

    $paymentDetails = PaymentDetails::where('quotation_id', $query->id)->get();

    $html = '<table class="table table-bordered">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Payment Date</th>
                        <th>Paid Amount</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>';

    if($paymentDetails->count()){
        $i = 1;
        foreach($paymentDetails as $payment){
            $html .= '<tr>
                        <td>'.$i++.'</td>
                        <td>'.($payment->payment_date ?? '-').'</td>
                        <td>'.$payment->amount.'</td>
                        <td>'.$payment->remarks.'</td>
                      </tr>';
        }
    } else {
        $html .= '<tr>
                    <td colspan="4" style="text-align:center">No Payments</td>
                  </tr>';
    }

    $html .= '</tbody></table>';

    return $html;
})



->rawColumns(['download', 'action', 'payment_details', 'lr_details'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<TrashedQuotation>
     */
    public function query(Quotation $model): QueryBuilder
    {
        return $model->newQuery()->onlyTrashed();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
        ->setTableId('trashed-quotation-table')
        ->columns($this->getColumns())
        ->minifiedAjax()
        ->orderBy(1)
        ->selectStyleSingle();
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
             Column::make('id')->title('S.No'),
            Column::make('quotation_no')->title('Quotation No'),
            Column::make('quotation_date')->title('Quotation Date'),
            Column::make('customer_id')->title('Customer Name'),
            Column::make('download')->title('Download'),
            Column::make('is_production_moved')->title('Production Moved?'),
            Column::make('batch_date')->title('Batch'),
            Column::make('priority')->title('Priority'),
            Column::make('production_status')->title('Production Status'),
            Column::make('action')->title('Action'),
            Column::make('payment_details')->title('Payment Details'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'TrashedQuotation_' . date('YmdHis');
    }
}
