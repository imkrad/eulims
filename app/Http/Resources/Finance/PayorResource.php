<?php

namespace App\Http\Resources\Finance;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {   
        if($this->code){
            return [
                'name' => ($this->name != 'Main') ? $this->customer_name->name.' - '.$this->name : $this->customer_name->name,
                'address' => $this->address->barangay->name.', '.$this->address->municipality->name.', '.$this->address->province->name.', '.$this->address->region->region,
            ];
        }else{  
            return [
                'name' => $this->name,
                'address' => 'Internal'
            ];
        }
        
    }
}
