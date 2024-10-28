<?php

namespace App\Services\Operation\Tsr;

use App\Models\Tsr;
use Hashids\Hashids;

class SaveClass
{
    public function save($request){
        $data = Tsr::create(array_merge($request->all(),[
            'status_id' => 1,
            'laboratory_id' => $this->laboratory,
            'customer_id' => $request->customer['value'],
            'conforme_id' => $request->conforme['value'],
            'received_by' => \Auth::user()->id
        ]));
        
        $payment = (in_array($request->discount_id, [5, 6, 7])) ? ['status_id' => 8,'is_free' => 1,'paid_at' => now()] : ['status_id' => 6];
        $data->payment()->create(array_merge($request->all(),$payment));

        $hashids = new Hashids('krad',10);
        $code = $hashids->encode($data->id);

        return [
            'data' => $code,
            'message' => 'TS Request creation was successful!', 
            'info' => "You've successfully created the new request."
        ];
    }
}
