<?php

namespace App\DataTables;

use App\Models\Task;
use App\Models\TaskReport;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TaskReportDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<TaskReport> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'taskreport.action')
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<TaskReport>
     */
    public function query(Task $model): QueryBuilder
    {
        $query = $model->newQuery()->with('assignedTo');

        // Apply filters if present
        if ($this->request()->has('employeeIds') && !empty($this->request()->employeeIds)) {
            $query->where('assigned_to', $this->request()->employeeIds);
        }

        if ($this->request()->has('status') && !empty($this->request()->status)) {
            $query->where('status', $this->request()->status);
        }

        if ($this->request()->has('fromdate') && $this->request()->has('todate') && !empty($this->request()->fromdate) && !empty($this->request()->todate)) {
            $query->whereBetween('task_date', [$this->request()->fromdate, $this->request()->todate]);
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('taskReport')
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
            Column::make('title')->title('Title'),
            Column::make('task_date')->title('Task Date'),
            Column::make('task_details')->title('Task Details'),
            Column::make('status')->title('Status'),
            Column::make('assigned_to')->title('Assigned To'),
            Column::make('priority')->title('Priority'),
            Column::make('task_type')->title('Task Type'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'TaskReport_' . date('YmdHis');
    }
}
