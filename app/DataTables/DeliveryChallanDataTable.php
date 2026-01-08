<?php

namespace App\DataTables;

use App\Models\DeliveryChallan;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class DeliveryChallanDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<DeliveryChallan> $query Results from query() method.
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

                <a class='dropdown-item' href='/download-delivery-challan/{$query->id}'>Download</a>
            </div>
            </div>
                ";
})
->addColumn('challan_received', function($query){
        return "<button class='btn btn-success challanReceived' data-challanid='{$query->id}'>Yes</button>";
})
->addColumn('status', function($query){
    if($query->status == 'created')
    {
        return "<span class='badge badge-primary'>Created</span>";
    }
    elseif($query->status == 'pending')
    {
        return "<span class='badge badge-danger'>Pending</span>";
    }
    else
    {
        return "<span class='badge badge-success'>Received</span>";
    }



})
->rawColumns(['challan_received', 'action', 'status'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<DeliveryChallan>
     */
    public function query(DeliveryChallan $model): QueryBuilder
    {
        return $model->orderBy('id', 'DESC')->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
         return $this->builder()
        ->setTableId('delivery-challan-table')
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
            Column::make('delivery_challan_id')->title('Delivery Challan ID'),
            Column::make('delivery_challan_date')->title('Date'),
            Column::make('status')->title('Status'),
            Column::make('challan_received')->title('Delivery Challan Received (Yes/No)'),
            Column::make('action')->title('Action'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'DeliveryChallan_' . date('YmdHis');
    }
}
