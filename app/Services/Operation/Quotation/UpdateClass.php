<?php

namespace App\Services\Operation\Quotation;

use App\Models\Quotation;
use App\Models\QuotationSample;
use App\Models\QuotationAnalysis;

class UpdateClass
{
    public function confirm($request){
        $data = Quotation::where('id',$request->id)->first();
        $data->status_id = $request->status_id;
        $data->due_at = $request->due_at;
        $data->code = $this->generateCode($data);
        $data->terms = json_encode($request->terms);
        $data->save();
        return [
            'data' => $data,
            'message' => 'Quotation was successfully confirmed!', 
            'info' => "You've successfully updated the quotation status.",
        ];
    }

    public function cancel($request){
        $data = Quotation::find($request->id);
        $data->update($request->except(['option']));
        
        return [
            'data' => $data,
            'message' => 'Quotation cancellation was successful!', 
            'info' => "You've successfully updated the quotation status.",
        ];
    }

    public function removeSample($request){
        $data = QuotationSample::find($request->id);
        $fee = $data->analyses()->sum('fee');
        if($data->delete()){
            $payment = Quotation::with('discounted')->where('id',$request->quotation_id)->first();
            $subtotal = (float) trim(str_replace(',','',$payment->subtotal),'₱ ');
            $total = (float) trim(str_replace(',','',$payment->total),'₱ ');
            if($payment->discount_id === 1){
                $discount = 0;
                $subtotal = $subtotal - $fee;
                $total = $total - $fee;
            }else{
                $subtotal = $subtotal - $fee;
                $discount = (float) (($payment->discounted->value/100) * $subtotal);
                $total =  ((float) $total - (float) $discount);
            }
            $payment->subtotal = $subtotal;
            $payment->discount = $discount;
            $payment->total = $total;
            if($payment->save()){
                return [
                    'data' => [],
                    'message' => 'Sample was removed!', 
                    'info' => "You've successfully remove the sample."
                ];
            }
        }
    }

    public function removeAnalysis($request){
        $data = QuotationAnalysis::find($request->id);
        $fee = (float) trim(str_replace(',','',$data->fee),'₱ ');
        if($data->delete()){
            $payment = Quotation::with('discounted')->where('id',$request->quotation_id)->first();
            $subtotal = (float) trim(str_replace(',','',$payment->subtotal),'₱ ');
            $total = (float) trim(str_replace(',','',$payment->total),'₱ ');
            if($payment->discount_id === 1){
                $discount = 0;
                $subtotal = $subtotal - $fee;
                $total = $total - $fee;
            }else{
                $subtotal = $subtotal - $fee;
                $discount = (float) (($payment->discounted->value/100) * $subtotal);
                $total =  ((float) $total - (float) $discount);
            }
            $payment->subtotal = $subtotal;
            $payment->discount = $discount;
            $payment->total = $total;
            if($payment->save()){
                return [
                    'data' => [],
                    'message' => 'Analysis was removed!', 
                    'info' => "You've successfully remove the analysis."
                ];
            }
        }
    }

    public function quotationSample($request){
        $data = QuotationSample::findOrFail($request->id)->update($request->all());
        return [
            'data' => $data,
            'message' => 'Quotation update was successful!', 
            'info' => "You've successfully updated a sample."
        ];
    }
}
