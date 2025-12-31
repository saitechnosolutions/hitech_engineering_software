<?php

namespace App\DataTables;

use App\Models\QuotationProducts;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use App\Models\EmployeeWiseProductionReport;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class EmployeeWiseProductionReportDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<EmployeeWiseProductionReport> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('employee_name', function ($query) {
                return $query->msFabricationEmployee?->name;;
            })
            ->addColumn('quotation_no', function ($query) {
                return $query->quotation?->quotation_no;
            })
            ->addColumn('product_name', function ($query) {
                return $query->product?->product_name;
            })
            ->addColumn('team_name', function ($query) {
                return "";
            })
            ->addColumn('product_quantity', function ($query) {
                return $query->quantity;
            })

            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<EmployeeWiseProductionReport>
     */
    public function query(QuotationProducts $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('employeeWiseProductionReport')
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
            Column::make('employee_name')->title('Employee Name'),
            Column::make('quotation_no')->title('Quotation No'),
            Column::make('product_name')->title('Product Name'),
            Column::make('team_name')->title('Team Name'),
            Column::make('product_quantity')->title('Production Quantity'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'EmployeeWiseProductionReport_' . date('YmdHis');
    }
}
