<?php

namespace App\Http\Resources\Finance;

use Hashids\Hashids;
use App\Http\Resources\Operation\CustomerResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TsrNoPaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $hashids = new Hashids('krad',10);
        $code = $hashids->encode($this->id);

        return [
            'id' => $this->id,
            'qr' => $code,
            'code' => $this->code,
            'customer' => new CustomerResource($this->customer),
            'payment' => $this->payment,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
