<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        $employeeName = '';
        $teamName = '';
        if(!empty($this->ms_fabrication_emp_id))
        {
            $employeeName = $this->msFabricationEmployee?->name;
            $teamName = 'MS Fabrication Team';
        }
        else
        {
            $employeeName = $this->ssFabricationEmployee?->name;
            $teamName = 'SS Fabrication Team';
        }
        

        return [
            "id" => $this->id,
            "employee_name" => $employeeName,
            "quotation_no" => $this->quotation?->quotation_no,
            "product_name" => $this->product?->product_name,
            "team_name" => $teamName,
            "product_qty" => $this->product?->quantity
        ];
    }
}