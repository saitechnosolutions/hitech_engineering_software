<?php

namespace App\DataTables;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use App\Models\StockInwardOutwardDetails;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class StockInwardOutwardDetailsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<StockInwardOutwardDetail> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'stockinwardoutwarddetails.action')
            ->addColumn('product_id', function($query){
                return $query?->product?->product_name;
            })
            ->addColumn('quotation_id', function($query){
                return $query?->quotation?->quotation_no;
            })
            ->addColumn('quotation_batch_id', function($query){
                return $query?->quotationBatch?->batch_date;
            })
            ->addColumn('stock_status', function($query){

                if($query->stock_status == 'stock_in')
                {
                    return "<span class='badge badge-success'>Stock In</span>";
                }
                else
                {
                    return "<span class='badge badge-danger'>Stock Out</span>";
                }
            })
            ->rawColumns(['stock_status'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<StockInwardOutwardDetail>
     */
    public function query(StockInwardOutwardDetails $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
        ->setTableId('stock-in-out-table')
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
            Column::make('product_id')->title('Product Name'),
            Column::make('inward_qty')->title('Inward Qty'),
            Column::make('inward_date')->title('Inward Date'),
            Column::make('outward_qty')->title('Outward Qty'),
            Column::make('outward_date')->title('Outward Date'),
            Column::make('quotation_id')->title('Quotation No'),
            Column::make('quotation_batch_id')->title('Quotation Batch No'),
            Column::make('stock_status')->title('Stock Status'),

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'StockInwardOutwardDetails_' . date('YmdHis');
    }
}
