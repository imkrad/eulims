<?php

namespace App\Services\Reference;

use App\Models\ListProgram;
use App\Models\ListStatus;
use App\Models\ListAgency;
use App\Models\ListDropdown;
use App\Models\ListPrivilege;
use App\Models\LocationRegion;
use App\Models\LocationProvince;
use App\Models\LocationMunicipality;
use App\Models\LocationBarangay;
use App\Http\Resources\DefaultResource;

class SaveClass
{
    public function program($request){
        $data = ListProgram::create($request->all());
        return [
            'data' => new DefaultResource($data),
            'message' => 'Program created successfully.', 
            'info' => 'The new program has been added.'
        ];
    }

    public function status($request){
        $data = ListStatus::create($request->all());
        return [
            'data' => new DefaultResource($data),
            'message' => 'Status created successfully.', 
            'info' => 'The new status has been added.'
        ];
    }

    public function privilege($request){
        $data = ListPrivilege::create($request->all());
        return [
            'data' => new DefaultResource($data),
            'message' => 'Privilege created successfully.', 
            'info' => 'The new privilege has been added.'
        ];
    }

    public function agency($request){
        $data = ListAgency::create($request->all());
        return [
            'data' => new DefaultResource($data),
            'message' => 'Agency created successfully.', 
            'info' => 'The new agency has been added.'
        ];
    }

    public function province($request){
        $data = LocationProvince::create(array_merge($request->all(),['region_code' => $request->region_code['value']]));
        return [
            'data' => new DefaultResource($data),
            'message' => 'Province created successfully.', 
            'info' => "The new province has been added."
        ];
    }

    public function dropdown($request){
        $data = ListDropdown::create($request->all());
        return [
            'data' => new DefaultResource($data),
            'message' => $request->classification.' created successfully.', 
            'info' => 'The new '.$request->classification.' has been added.'
        ];
    }
}
