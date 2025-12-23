<?php

namespace App\DataTables;

use App\Models\ComponentStockReport;
use App\Models\ProductComponents;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ComponentStockReportDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<ComponentStockReport> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'componentstockreport.action')
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<ComponentStockReport>
     */
    public function query(ProductComponents $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
       return $this->builder()
        ->setTableId('component-stock-table')
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
            Column::make('component_name')->title('Component Name'),
            Column::make('unit_price')->title('Price'),
            Column::make('unit_price')->title('Unit Price'),
            Column::make('stock_qty')->title('Available Quantity'),

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'ComponentStockReport_' . date('YmdHis');
    }
}
