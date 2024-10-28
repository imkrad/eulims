<?php

namespace App\Services\Operation\Services;

use App\Models\Testservice;
use App\Models\TestserviceName;
use App\Models\TestserviceMethod;

class SaveClass
{
    public function create($request){
        $service = Testservice::create(array_merge($request->all()));
        return [
            'data' => $service,
            'message' => 'Testservice creation was successful!', 
            'info' => "You've successfully created the new testservice."
        ];
    }

    public function add($request){
        $name = TestserviceName::create($request->all());
        $data = TestserviceName::findOrFail($name->id);
        $data = [
            'value' => $data->id,
            'name' => $data->name,
        ];
        return $data;
    }

    public function method($request){
        $method = TestserviceMethod::create($request->all());
        $data = TestserviceMethod::with('method')->where('id',$method->id)->first();
        return $data;
    }

    public function fee($request){
        $data = Testservice::findOrFail($request->id);
        $data->fees()->create($request->all());
        return [
            'data' => $data,
            'message' => 'Additional fee added was successful!', 
            'info' => "You've successfully added additional fee."
        ];
    }
}
