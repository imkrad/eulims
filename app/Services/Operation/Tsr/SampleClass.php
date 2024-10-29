<?php

namespace App\Services\Operation\Tsr;

use Hashids\Hashids;
use App\Models\TsrSample;
use App\Models\TsrPayment;
use App\Models\Configuration;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class SampleClass
{
    public function __construct()
    {
        $this->laboratory = (\Auth::user()->myrole) ? \Auth::user()->myrole->laboratory_id : null;
        $this->configuration = Configuration::with('laboratory.address')->where('laboratory_id',$this->laboratory)->first();
    }

    public function save($request){
        $data = TsrSample::create($request->all());
        $data = TsrSample::with('analyses.status','analyses.testservice.method.method','analyses.sample','analyses.analyst')->where('id',$data->id)->first();
        
        return [
            'data' => $data,
            'message' => 'Sample added was successful!', 
            'info' => "You've successfully created the new sample."
        ];
    }

    public function update($request){
        $data = TsrSample::findOrFail($request->id);
        $data->name = $request->name;
        $data->customer_description = $request->customer_description;
        $data->description = $request->description;
        $data->save();
        return [
            'data' => $data,
            'message' => 'Sample update was successful!', 
            'info' => "You've successfully updated the selected sample."
        ];
    }

    public function remove($request){
        $id = $request->id;
        $tsr_id = $request->tsr_id;
        $data = TsrSample::find($id);
        $fee = $data->analyses()->sum('fee');
        if($data->delete()){
            $payment = TsrPayment::with('discounted')->where('tsr_id',$tsr_id)->first();
            $subtotal = (float) trim(str_replace(',','',$payment->subtotal),'₱ ');
            $total = (float) trim(str_replace(',','',$payment->total),'₱ ');
            if($payment->discount_id === 1){
                $discount = 0;
                $subtotal = $subtotal - $fee;
                $total = $total - $fee;
            }else{
                $subtotal = $subtotal - $fee;
                $discount = (float) (($payment->discounted->value/100) * $subtotal);
                $total =  ((float) $subtotal - (float) $discount);
            }
            $payment->subtotal = $subtotal;
            $payment->discount = $discount;
            $payment->total = $total;
            $payment->save();
        }
        return [
            'data' => $payment,
            'message' => 'Sample was removed successful!', 
            'info' => "You've successfully remove the sample."
        ];
    }

    public function qr($request){
        $sample = TsrSample::with('analyses:id,sample_id,testservice_id','analyses.testservice:id,testname_id','analyses.testservice.testname:id,name')
        ->with('tsr:id,due_at,created_at')
        ->where('id',$request->id)->first();
        $testnames = [];
      
        foreach ($sample->analyses as $analysis) {
            if (isset($analysis->testservice->testname->name)) {
                $testnames[] = $analysis->testservice->testname->name;
            }
        }
       
        $code = $sample->code;
        $qrCode = new QrCode($code);
        $qrCode->setSize(300);
        $pngWriter = new PngWriter();
        $qrCodeImageString = $pngWriter->write($qrCode)->getString();
        $base64Image = 'data:image/png;base64,' . base64_encode($qrCodeImageString);

        $array = [
            'qrCodeImage' => $base64Image,
            'sample_code' => $code,
            'sample_name' => $sample->name,
            'due_at' => $sample->tsr->due_at,
            'created_at' => $sample->tsr->created_at,
            'testnames' => $testnames
        ];
        $width = 6.20 * 28.35; 
        $height = 6.00 * 28.35;
        $pdf = \PDF::loadView('printings.sampleqrcode',$array)->setPaper([0, 0, $width, $height], 'portrait');

        return $pdf->stream('sampleqrcode.pdf');
    }

    public function allqr($request){
        $hashids = new Hashids('krad',10);
        $id = $hashids->decode($request->id);
        $samples = TsrSample::with('analyses:id,sample_id,testservice_id','analyses.testservice:id,testname_id','analyses.testservice.testname:id,name')
        ->with('tsr:id,due_at,created_at')
        ->where('tsr_id',$id)->get();
        $lists = [];
        foreach($samples as $sample){
            $testnames = [];

            foreach ($sample->analyses as $analysis) {
                if (isset($analysis->testservice->testname->name)) {
                    $testnames[] = $analysis->testservice->testname->name;
                }
            }

            $code = $sample->code;
            $qrCode = new QrCode($code);
            $qrCode->setSize(300);
            $pngWriter = new PngWriter();
            $qrCodeImageString = $pngWriter->write($qrCode)->getString();
            $base64Image = 'data:image/png;base64,' . base64_encode($qrCodeImageString);

            $lists[] = [
                'qrCodeImage' => $base64Image,
                'sample_code' => $code,
                'sample_name' => $sample->name,
                'due_at' => $sample->tsr->due_at,
                'created_at' => $sample->tsr->created_at,
                'testnames' => $testnames
            ];
        }
        $array = [
            'lists' => $lists
        ];
        $width = 6.20 * 28.35; 
        $height = 6.00 * 28.35;
        $pdf = \PDF::loadView('printings.allsampleqrcode',$array)->setPaper([0, 0, $width, $height], 'portrait');

        return $pdf->stream('allsampleqrcode.pdf');
    }
}
