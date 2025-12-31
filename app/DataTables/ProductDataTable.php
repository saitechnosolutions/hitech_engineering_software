<?php

namespace App\DataTables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Product> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('product_image', function($query){
                return '<img src="'.$query->product_image.'" style="width:100px" class="img-thumbnail">';
            })
            ->addColumn('category_id', function($query){
                return $query->category->name ?? null;
            })
            ->addColumn('design_sheet', function($query){
                return '<a href="'.$query->design_sheet.'" download>Download</a>';
            })
            ->addColumn('data_sheet', function($query){
                return '<a href="'.$query->data_sheet.'" download>Download</a>';
            })
            ->addColumn('data_sheet', function($query){
                return '<a href="'.$query->data_sheet.'" download>Download</a>';
            })
            ->addColumn('bom_count', function($query){
                return $query->bomParts->count();
            })
            ->addColumn('action', function($query){
                return "
                    <div class='dropdown'>
            <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                <i class='fa fa-cog' aria-hidden='true'></i>
            </button>
            <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                <a class='dropdown-item' href='/products/show/{$query->id}'>View</a>
                <a class='dropdown-item' href='/products/edit/{$query->id}'>Edit</a>
                <a class='dropdown-item deleteBtn' data-url='/products/delete/{$query->id}'>Delete</a>
            </div>
            </div>
                ";
})
            ->rawColumns(['product_image', 'design_sheet', 'data_sheet', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Product>
     */
    public function query(Product $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
        ->setTableId('product-table')
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
            Column::make('category_id')->title('Category Name'),
            Column::make('product_name')->title('Product Name'),
            Column::make('product_image')->title('Product Image'),
            Column::make('bom_count')->title('BOM Count'),
            Column::make('brand')->title('Brand'),
            Column::make('bike_model')->title('Bike Model'),
            Column::make('mrp_price')->title('MRP Price'),
            Column::make('part_number')->title('Part Number'),
            Column::make('quantity')->title('Quantity'),
            Column::make('variation')->title('Variation'),
            Column::make('hsn_code')->title('HSN Code'),
            Column::make('design_sheet')->title('Design Sheet'),
            Column::make('data_sheet')->title('Data Sheet'),
            Column::make('action')->title('Action'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Product_' . date('YmdHis');
    }
}
