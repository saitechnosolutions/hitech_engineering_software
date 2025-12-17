<?php

namespace App\DataTables;

use App\Models\Quotation;
use App\Models\CollectionReport;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class CollectionReportDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<CollectionReport> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('company_name', function($query){
                return $query?->customer?->customer_name;
            })
            ->addColumn('quotation_no', function($query){
                return $query?->quotation_no;
            })
            ->addColumn('quotation_date', function($query){
                return formatDate($query?->quotation_date);
            })
            ->addColumn('received_amount', function($query){
                return number_format($query->payments->sum('amount'), 2);
            })
            ->addColumn('pending_amount', function($query){
                return number_format($query->total_collectable_amount - $query->payments->sum('amount'), 2);
            })


            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<CollectionReport>
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
        ->setTableId('component-table')
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
            Column::make('company_name')->title('Company Name'),
            Column::make('quotation_no')->title('Quotation No'),
            Column::make('quotation_date')->title('Quotation Date'),
            Column::make('total_collectable_amount')->title('Quotation Amount'),
            Column::make('received_amount')->title('Received Amount'),
            Column::make('pending_amount')->title('Pending Amount'),

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'CollectionReport_' . date('YmdHis');
    }
}
