<?php

namespace App\Services\Operation\Quotation;

use App\Models\Tsr;
use App\Models\Quotation;
use App\Models\QuotationSample;
use App\Models\QuotationService;
use App\Models\QuotationAnalysis;

class SaveClass
{
    public function __construct()
    {
        $this->laboratory = (\Auth::user()->myrole) ? \Auth::user()->myrole->laboratory_id : null;
    }

    public function tsr($request){
        $id = $request->id;
        $data = Tsr::create([
            'customer_id' => $request->customer_id,
            'conforme_id' => ($request->conforme) ? $request->conforme['value'] : $request->conforme_id,
            'purpose_id' => $request->purpose_id,
            'laboratory_id' => $request->laboratory_id,
            'laboratory_type' => $request->laboratory_type,
            'status_id' => 1,
            'received_by' => \Auth::user()->id
        ]);
        if($data){
            $data->payment()->create([
                'total' => $request->total,
                'subtotal' => $request->subtotal,
                'discount' => $request->discount,
                'discount_id' => $request->discount_id,
                'status_id' => 6,
                'is_free' =>  (in_array($request->discount_id, [5, 6, 7])) ? 1 : 0,
                'paid_at' =>  (in_array($request->discount_id, [5, 6, 7])) ? now() : NULL,
            ]);
            $samples = QuotationSample::with('analyses.addfee.service')->where('quotation_id',$id)->get();

            foreach($samples as $sample){
                $s = $data->samples()->create([
                    'name' => $sample['name'],
                    'customer_description' => $sample['customer_description'],
                    'description' => $sample['description'],
                    'tsr_id' => $data->id
                ]);
                foreach($sample['analyses'] as $analysis){
                    $s->analyses()->create([
                        'fee' => $analysis['fee'],
                        'testservice_id' => $analysis['testservice_id'],
                        'sample_id' =>$s->id,
                        'status_id' => 10
                    ]);
                }
            }
            $services = QuotationService::where('typeable_id',$id)->where('typeable_type','App\Models\Quotation')->get();
            foreach($services as $service){
                $data->service()->create([
                    'fee' => $service['fee'],
                    'total' => $service['total'],
                    'quantity' => $service['quantity'],
                    'service_id' => $service['service_id'],
                    'is_additional' => $service['is_additional'],
                ]);
            }
            $status = Quotation::where('id',$id)->update(['status_id' => 16]);
        }
        $hashids = new Hashids('krad',10);
        $code = $hashids->encode($data->id);

        return [
            'data' => $code,
            'message' => 'TS Request creation was successful!', 
            'info' => "You've successfully created the new request."
        ];
    }

    public function quotation($request){
        $data = Quotation::create(array_merge($request->all(),[
            'status_id' => 14,
            'laboratory_id' => $this->laboratory,
            'customer_id' => $request->customer['value'],
            'conforme_id' => $request->conforme['value'],
            'created_by' => \Auth::user()->id
        ]));

        return [
            'data' => $data,
            'message' => 'Quotation creation was successful!', 
            'info' => "You've successfully created the new quotation."
        ];
    }

    public function service($request){
        $data = Quotation::findOrFail($request->id);
        $data->service()->create([
            'service_id' => $request->service['value'],
            'fee' => $request->service['fee'],
            'quantity' => $request->quantity,
            'total' => $request->total,
        ]);
        $total = $this->updateTotal($request->id,$request->total);
        return [
            'data' => $total,
            'message' => 'Service added was successful!', 
            'info' => "You've successfully added a service."
        ];
    }

    public function fee($request){
        $data = QuotationAnalysis::findOrFail($request->id);
        $data->addfee()->create([
            'service_id' => $request->service['id'],
            'fee' => $request->service['fee'],
            'total' => $request->total,
            'quantity' => $request->quantity,
            'is_additional' => 1
        ]);
        $total = $this->updateTotal($request->tsr_id,$request->total);
        return [
            'data' => $total,
            'message' => 'Service added was successful!', 
            'info' => "You've successfully added a service."
        ];
    }

    public function sample($request){
        $data = QuotationSample::create($request->all());
        return [
            'data' => $data,
            'message' => 'Sample added was successful!', 
            'info' => "You've successfully created the new sample."
        ];
    }

    public function analyses($request){
        foreach($request->samples as $sample){
            foreach($request->lists as $list){
                $data = QuotationAnalysis::create(array_merge($request->all(),[
                    'status_id' => 10,
                    'testservice_id' => $list['id'],
                    'fee' => $list['fee'],
                    'sample_id' => $sample
                ]));
                $total =  $this->updateTotal($data->sample->quotation_id,$list['fee']);
            }
        }
        return [
            'data' => $total,
            'message' => 'Analysis added was successful!', 
            'info' => "You've successfully created the new analysis."
        ];
    }

    private function updateTotal($id,$fee){
        $data = Quotation::with('discounted')->where('id',$id)->first();
        $fee = (float) trim(str_replace(',','',$fee),'₱ ');
        $subtotal = (float) trim(str_replace(',','',$data->subtotal),'₱ ');
        if($data->discount_id === 1){
            $discount = 0;
            $subtotal = $subtotal + $fee;
            $total = $subtotal;
        }else{
            $subtotal = $subtotal + $fee;
            $discount = (float) (($data->discounted->value/100) * $subtotal);
            $total =  ((float) $subtotal - (float) $discount);
        }
        $data->subtotal = $subtotal;
        $data->discount = $discount;
        $data->total = $total;
        $data->save();
        return $data->total;
    }
}
