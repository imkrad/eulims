<?php

namespace App\Http\Resources\Finance;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReceiptResource extends JsonResource
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
            'id' => $this->id,
            'number' => $this->number,
            'customer' => ($this->op->payorable->customer_name) ? ($this->op->payorable->customer_name->name == 'Main') ? $this->op->payorable->customer_name->name : $this->op->payorable->customer_name->name.' - '.$this->op->payorable->name  : $this->op->payorable->name,
            'address' => ($this->op->payorable->customer_name) ? $this->op->payorable->address->barangay->name.', '.$this->op->payorable->address->municipality->name.', '.$this->op->payorable->address->province->name : '-',
            'collection' => $this->op->collection->name,
            'total' => $this->op->total,
            'items' => $this->op->items,
            'status' => $this->op->status,
            'payment' => $this->op->payment->name,
            'detail' => $this->detail,
            'transaction' => $this->transaction,
            'user' => $this->createdby->profile->firstname.' '.$this->createdby->profile->lastname,
            'date' => $this->created_at
        ];
    }
}
