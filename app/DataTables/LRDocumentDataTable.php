<?php

namespace App\DataTables;

use App\Models\LRDocument;
use App\Models\LRDocuments;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class LRDocumentDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<LRDocument> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'lrdocument.action')
            ->addColumn('quotation_id', function($query){
                return $query->quotation?->quotation_no;
            })
            ->addColumn('entry_date', function($query){
                return formatDate($query->entry_date);
            })
            ->addColumn('action', function($query){
                return "
                    <div class='dropdown'>
            <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                <i class='fa fa-cog' aria-hidden='true'></i>
            </button>
            <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                <a class='dropdown-item' href='/roles/$query->id/give-permission'>Add Permission</a>
                <a class='dropdown-item' href='/roles/edit/{$query->id}'>Edit</a>
                <a class='dropdown-item deleteBtn' data-url='/roles/delete/{$query->id}'>Delete</a>
            </div>
            </div>
                ";
})
            ->addColumn('upload_documents', function($query){
                $files = json_decode($query->upload_documents, true);

    if (!$files) {
        return '-';
    }

    $html = '';

    foreach ($files as $file) {

        $url = asset($file);

        $html .= '<a href="'.$url.'" target="_blank" class="btn btn-sm btn-success" title="Download">
                    <i class="fa fa-download"></i>
                  </a> ';
    }

    return $html;
            })
            ->rawColumns(['upload_documents', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<LRDocument>
     */
    public function query(LRDocuments $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
         return $this->builder()
        ->setTableId('lrdocument-table')
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
            Column::make('quotation_id')->title('Quotation No'),
            Column::make('entry_date')->title('Date'),
            Column::make('upload_documents')->title('Documents'),
            Column::make('remarks')->title('Remarks'),
            Column::make('action')->title('Action'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'LRDocument_' . date('YmdHis');
    }
}