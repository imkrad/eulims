<?php

namespace App\Services\Operation\Customer;

use App\Models\Laboratory;
use App\Models\Customer;
use App\Models\CustomerName;
use App\Models\CustomerConforme;
use App\Models\Configuration;

class SaveClass
{
    public $laboratory, $configuration;

    public function __construct()
    {
        $this->laboratory = (\Auth::user()->myrole) ? \Auth::user()->myrole->laboratory_id : null;
        $this->configuration = Configuration::with('laboratory.address')->where('laboratory_id',$this->laboratory)->first();
    }

    public function save($request){
        if($request->customer['value'] === $request->customer['name']){
            $name = new CustomerName;
            $name->name = $request->customer['value'];
            $name->has_branches = $request->has_branches;
            if($name->save()){
                $request['name_id'] = $name->id;
            }
        }else{
            $request['name_id'] = $request->customer['value'];
        }
        $code = $this->generateCode();
        $customer = Customer::create(array_merge($request->all(),['code' => $code,'laboratory_id' => $this->laboratory,'user_id' => \Auth::user()->id]));
        $customer->address()->create($request->except(['name','is_main','email','industry_id','classification_id','contact_no','name_id','customer','has_branches','option']));
        $customer->contact()->create($request->all());

        return [
            'data' => $customer,
            'message' => 'Customer creation was successful!', 
            'info' => "You've successfully created the new customer."
        ];
    }

    public function update($request){
        $data = Customer::findOrFail($request->id);
        $data->updateIfDirty($request->only('industry_id','classification_id'));
        $data->contact->updateIfDirty($request->only('email','contact_no'));
        $data->address->updateIfDirty($request->only('province_code','municipality_code','barangay_code','address'));
        return [
            'data' => $data,
            'message' => 'Customer was updated!', 
            'info' => "You've successfully created the new customer."
        ];
    }

    public function conforme($request){
        $data = CustomerConforme::findOrFail($request->id);
        $data->name = $request->name;
        $data->contact_no = $request->contact_no;
        $data->save();

        return [
            'data' => $data,
            'message' => 'Conforme was updated!', 
            'info' => "You've successfully updated the conforme."
        ];
    }

    private function generateCode(){
        $lab = Laboratory::where('id',$this->laboratory)->first();
        $c = Customer::where('laboratory_id',$this->laboratory)->count();
        $code = $lab->code.'-'.'CSTMR-'.str_pad(($c+1), 5, '0', STR_PAD_LEFT);  
        return $code;
    }
}
