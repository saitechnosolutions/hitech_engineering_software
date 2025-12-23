<?php

namespace App\DataTables;

use App\Models\Quotation;
use App\Models\SalesStockReport;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class SalesStockReportDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<SalesStockReport> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('customer', function($query){
                return $query->customer?->name;
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<SalesStockReport>
     */
    public function query(Quotation $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
        ->setTableId('sales-stock-table')
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
            Column::make('customer')->title('Customer'),
            Column::make('batch_date')->title('Batch'),
            Column::make('total_collectable_amount')->title('Quotation Amount'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'SalesStockReport_' . date('YmdHis');
    }
}