<?php

namespace App\Services\Reference;

use App\Models\ListStatus;
use App\Models\ListPrivilege;
use App\Models\LocationRegion;
use App\Models\LocationProvince;
use App\Models\LocationMunicipality;
use App\Models\LocationBarangay;
use App\Http\Resources\DefaultResource;

class UpdateClass
{
    public function status($request){
        $data = ListStatus::where('id',$request->id)->first();
        $data->name = $request->name;
        $data->type = $request->type;
        $data->others = $request->others;
        $data->color = $request->color;
        $data->save();

        return [
            'data' => new DefaultResource($data),
            'message' => 'Status updated successfully.', 
            'info' => "The status details has been changed."
        ];
    }

    public function privilege($request){
        $data = ListPrivilege::where('id',$request->id)->first();
        ($request->name) ? $data->name = $request->name : '';
        ($request->type) ? $data->type = $request->type : '';
        ($request->short) ? $data->short = $request->short : '';
        ($request->regular_amount) ? $data->regular_amount = $request->regular_amount : '';
        ($request->summer_amount) ? $data->summer_amount = $request->summer_amount : '';
        ($request->is_fixed!= NULL) ? $data->is_fixed = $request->is_fixed : '';
        ($request->is_reimburseable!= NULL) ? $data->is_reimburseable = $request->is_reimburseable : '';
        $data->is_active = $request->is_active;
        $data->save();

        return [
            'data' => new DefaultResource($data),
            'message' => 'Privilege updated successfully.', 
            'info' => "The privilege details has been changed."
        ];
    }

    public function barangay($request){
        $data = LocationBarangay::where('id',$request->id)->first();
        $data->name = $request->name;
        $data->old_name = $request->old_name;
        $data->district = $request->district;
        $data->municipality_code = $request->municipality_code['code'];
        $data->save();

        return [
            'data' => new DefaultResource($data),
            'message' => 'Barangay updated successfully.', 
            'info' => "The barangay details has been changed."
        ];
    }

    public function municipality($request){
        $data = LocationMunicipality::where('id',$request->id)->first();
        $data->name = $request->name;
        $data->old_name = $request->old_name;
        $data->district = $request->district;
        $data->is_chartered = $request->is_chartered;
        $data->zipcode = $request->zipcode;
        $data->province_code = $request->province_code['code'];
        $data->save();

        return [
            'data' => new DefaultResource($data),
            'message' => 'Municipality updated successfully.', 
            'info' => "The municipality details has been changed."
        ];
    }
}
