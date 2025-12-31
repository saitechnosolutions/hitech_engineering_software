<?php

namespace App\DataTables;

use App\Models\Product;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\Models\ProductStockReport;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ProductStockReportDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<ProductStockReport> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'productstockreport.action')
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<ProductStockReport>
     */
    public function query(Product $model): QueryBuilder
    {
        $query = $model->newQuery();

        // Apply filters if present
        if ($this->request()->has('productIds') && !empty($this->request()->productIds)) {
            $query->where('id', $this->request()->productIds);
        }

        if ($this->request()->has('stocks') && !empty($this->request()->stocks)) {
            $stocks = $this->request()->stocks;
            if ($stocks === 'low') {
                $query->where('stock_qty', '<=', 10);
            } elseif ($stocks === 'high') {
                $query->where('stock_qty', '>', 10);
            }
        }

        return $query->orderBy('product_name');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
        ->setTableId('product-stock-table')
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
            Column::make('product_name')->title('Product Name'),
            Column::make('part_number')->title('Part Number'),
            Column::make('variation')->title('Variation'),
            Column::make('color')->title('Color'),
            Column::make('material')->title('Material'),
            Column::make('stock_qty')->title('Available Quantity'),
            
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'ProductStockReport_' . date('YmdHis');
    }
}