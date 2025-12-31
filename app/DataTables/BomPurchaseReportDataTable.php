<?php

namespace App\DataTables;

use App\Models\BOMParts;
use App\Models\BomPurchaseReport;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class BomPurchaseReportDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<BomPurchaseReport> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('total_qty', function($query){
                return '';
            })
            ->addColumn('batch', function($query){
                return '';
            })
            ->addColumn('quotation_no', function($query){
                return '';
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<BomPurchaseReport>
     */
    public function query(BOMParts $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
        ->setTableId('bom-purchase-report-table')
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
            Column::make('batch')->title('Batch'),
            Column::make('quotation_no')->title('Quotation No'),
            Column::make('bom_category')->title('BOM Type'),
            Column::make('bom_name')->title('BOM Name'),
            Column::make('bom_unit')->title('Total Unit'),
            Column::make('total_qty')->title('Total Qty'),


        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'BomPurchaseReport_' . date('YmdHis');
    }
}