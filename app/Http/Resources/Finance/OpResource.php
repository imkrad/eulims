<?php

namespace App\Http\Resources\Finance;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OpResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'total' => $this->total,
            'customer_id' => $this->payorable_id,
            'is_lab' => ($this->payorable_type == 'App\Models\Customers') ? true : false ,
            'or' => $this->or,
            'items' => $this->items,
            'payorable' => new PayorResource($this->payorable),
            'payment' => $this->payment,
            'collection' => $this->collection->name,
            'status' => $this->status,
            'user' => $this->createdby->profile->firstname.' '.$this->createdby->profile->lastname,
            'date' => date('F d, Y', strtotime($this->created_at))
        ];
    }
}
