<?php

namespace App\DataTables;

use App\Models\BomPart;
use App\Models\BOMParts;
use App\Models\BomProcessTeams;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class BomPartsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<BomPart> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'bomparts.action')
            ->addColumn('product_id', function($query){
                return $query->product->product_name;
            })
            ->addColumn('action', function($query){
                return "
                    <div class='dropdown'>
            <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                <i class='fa fa-cog' aria-hidden='true'></i>
            </button>
            <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                <a class='dropdown-item' href=''>Edit</a>
                <a class='dropdown-item deleteBtn' >Delete</a>
            </div>
            </div>
                ";
})
->addColumn('stages', function($query){
    $stageDetails = BomProcessTeams::where('bom_id', $query->id)->get();
    $i=1;
    $html = '<table class="table table-bordered">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Stages</th>
                    </tr>
                </thead>
                <tbody>';

    foreach($stageDetails as $stageDetail) {
        $html .= '<tr><td>'.$i++.'</td><td>' . $stageDetail?->team?->team_name . '</td></tr>';
    }

    $html .= '</tbody></table>';

    return $html;
})
->rawColumns(['stages', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<BomPart>
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
        ->setTableId('bomparts-table')
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
            Column::make('bom_name')->title('BOM Name'),
            Column::make('bom_unit')->title('BOM Unit'),
            Column::make('bom_qty')->title('BOM Qty'),
            Column::make('bom_price')->title('BOM Price'),
            Column::make('action')->title('Action'),
            Column::make('stages')->title('Stages'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'BomParts_' . date('YmdHis');
    }
}
