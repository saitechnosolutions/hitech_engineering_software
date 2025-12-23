<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentFilterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return [
            "id" => $this->id,
            "quotation_no" => $this->quotation->quotation_no,
            "payment_date" => formatDate($this->payment_date),
            "amount" => $this->amount,
            "remarks" => $this->remarks,
            "reference_images" => $this->reference_images,
              "action" => "
            <div class='dropdown'>
                <button class='btn btn-secondary dropdown-toggle' type='button' 
                        data-toggle='dropdown'>
                    <i class='fa fa-cog'></i>
                </button>

                <div class='dropdown-menu'>
                    <a class='dropdown-item' href='/payments/edit/{$this->id}'>Edit</a>
                    <a class='dropdown-item deleteBtn' data-url='/payments/delete/{$this->id}'>
                        Delete
                    </a>
                    <a class='dropdown-item' href='/payments/resource/{$this->id}'>
                        Payment Resource
                    </a>
                </div>
            </div>
        ",
        ];
    }
}