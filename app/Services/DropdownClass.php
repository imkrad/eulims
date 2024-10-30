<?php

namespace App\Services;

use App\Models\Configuration;
use App\Models\Laboratory;
use App\Models\ListStatus;
use App\Models\ListLaboratory;
use App\Models\ListIndustry;
use App\Models\ListDropdown;
use App\Models\ListDiscount;
use App\Models\LocationRegion;
use App\Models\LocationProvince;
use App\Models\LocationMunicipality;
use App\Models\LocationBarangay;
use App\Models\TestserviceAddon;

class DropdownClass
{   
    public function __construct()
    {
        $this->laboratory = (\Auth::check()) ? (\Auth::user()->userrole) ? \Auth::user()->myrole->laboratory_id : null : '';
        $this->ids =(\Auth::check()) ? (\Auth::user()->role == 'Administrator') ? [] : json_decode(Configuration::where('laboratory_id',$this->laboratory)->value('laboratories')) : '';
    }

    public function collections($type){
        $data = ListDropdown::where('classification','Collection Type')->where('type',$type)->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name
            ];
        });
        return $data;
    }
    
    public function payment_modes(){
        $data = ListDropdown::where('classification','Payment Mode')->where('type','n/a')->where('is_active',1)->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name,
                'others' => $item->others
            ];
        });
        return $data;
    }

    public function payment_statuses(){
        $data = ListStatus::where('type','Payment')->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name
            ];
        });
        return $data;
    }


    public function laboratories(){
        $data = Laboratory::with('member')->where('is_active',1)->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->member->name.' ('.$item->name.')',
                'short' => $item->name
            ];
        });
        return $data;
    }

    public function services(){
        $data = TestserviceAddon::where('is_additional',0)->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'label' => $item->name.' ('.$item->description.')',
                'name' => $item->name,
                'description' => $item->description,
                'fee' => $item->fee
            ];
        });
        return $data;
    }

    public function discounts(){
        $data = ListDiscount::with('based','type','subtype')->where('is_active',1)->get()->map(function ($item) {
            $total = ($item->subtype->name == 'Percentage') ? $item->value.'%' : '₱'.$item->value;
            $name = ($item->name === 'Regular') ? $item->name : $item->name.' ('.$total.')';
            return [
                'value' => $item->id,
                'name' => $name,
                'number' => $item->value,
                'type' => $item->type->name,
                'based' => $item->based->name,
                'subtype' => $item->subtype->name,
            ];
        });
        return $data;
    }

    public function statuses($type){
        $data = ListStatus::where('type',$type)->where('is_active',1)->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name,
                'type' => $item->type,
                'color' => $item->color,
                'others' => $item->others,
            ];
        });
        return $data;
    }

    public function modes(){
        $data = ListDropdown::where('classification','Mode of Release')->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name
            ];
        });
        return $data;
    }

    public function purposes(){
        $data = ListDropdown::where('classification','Purpose')->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name
            ];
        });
        return $data;
    }

    public function laboratory_types(){
        $lab_id = ($this->ids) ? array_column($this->ids, 'value') : null;
        $query = ListLaboratory::query();
        ($lab_id) ? $query->whereIn('id',$lab_id) : '';
        $data = $query->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name,
                'short' => $item->short
            ];
        });
        return $data;
    }

    public function industry_type(){
        $data = ListIndustry::where('is_active',1)->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'is_main' => $item->is_main,
                'is_alone' => $item->is_alone,
                'industry_id' => $item->industry_id,
                'name' => $item->name
            ];
        });
        return $data;
    }

    public function classes(){
        $data = ListDropdown::where('classification','Class')->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name
            ];
        });
        return $data;
    }

    public function regions(){
        $data = LocationRegion::all()->map(function ($item) {
            return [
                'value' => $item->code,
                'name' => $item->region
            ];
        });
        return $data;
    }

    public function provinces($code){
        $data = LocationProvince::where('region_code',$code)->get()->map(function ($item) {
            return [
                'value' => $item->code,
                'name' => $item->name
            ];
        });
        return $data;
    }

    public function municipalities($code){
        $data = LocationMunicipality::where('province_code',$code)->get()->map(function ($item) {
            return [
                'value' => $item->code,
                'name' => $item->name
            ];
        });
        return $data;
    }

    public function barangays($code){
        $data = LocationBarangay::where('municipality_code',$code)->get()->map(function ($item) {
            return [
                'value' => $item->code,
                'name' => $item->name
            ];
        });
        return $data;
    }
}
