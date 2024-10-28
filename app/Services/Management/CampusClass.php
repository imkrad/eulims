<?php

namespace App\Services\Management;

use App\Models\SchoolName;
use App\Models\SchoolCampus;
use App\Models\SchoolAddress;
use App\Http\Resources\DefaultResource;

class CampusClass
{
    public function lists($request){
        $data = DefaultResource::collection(
            SchoolCampus::with('school.class','grading','term','agency')
            ->with('address.region','address.province','address.municipality','address.barangay')
            ->when($request->keyword, function ($query, $keyword) {
                $query->where('campus', 'LIKE', "%{$keyword}%")
                ->orWhereHas('school', function ($query) use ($keyword) {
                    $query->where('name', 'LIKE', "%$keyword%");
                });
            })
            ->when($request->status, function ($query, $status) {
                $status = ($status == 'Active') ? 1 : 0;
                $query->where('is_active',$status);
            })
            ->when($request->grading, function ($query, $grading) {
                $query->where('grading_id',$grading);
            })
            ->when($request->term, function ($query, $term) {
                $query->where('term_id',$term);
            })
            ->when($request->agency, function ($query, $agency) {
                $query->where('agency_id',$agency);
            })
            ->orderBy('is_active','DESC')
            ->paginate($request->count)
        );
        return $data;
    }

    public function name($request){
        $data = SchoolName::create([
            'name' => $request->name,
            'campus_id' => $request->campus_id
        ]);

        return [
            'data' => $data,
            'message' => 'Name created successfully.', 
            'info' => 'The previous name has been added.',
        ];
    }

    public function save($request){
        $data = SchoolCampus::create([
            'campus' => $request->campus,
            'school_id' => $request->school_id,
            'agency_id' => $request->agency_id,
            'term_id' => $request->term_id,
            'grading_id' => $request->grading_id,
            'is_main' => 0
        ]);
        if($data){
            $data->address()->create([
                'address' => $request->info,
                'barangay_code' => $request->barangay['value'],
                'municipality_code' => $request->municipality['value'],
                'province_code' => $request->province['value'],
                'region_code' => $request->region['value'],
            ]);
        }
        return [
            'data' => $data,
            'message' => 'Campus created successfully.', 
            'info' => 'The new school has been added.',
        ];
    }

    public function update($request){
        $data = SchoolCampus::where('id',$request->id)->first();
        ($request->campus) ? $data->campus = $request->campus : '';
        ($request->grading_id) ? $data->grading_id = $request->grading_id : '';
        ($request->term_id) ? $data->term_id = $request->term_id : '';
        ($request->agency_id) ? $data->agency_id = $request->agency_id : '';
        if($data->save()){
            $data = SchoolAddress::where('campus_id',$request->id)->first();
            ($request->address) ? $data->address = $request->info : '';
            ($request->region) ? $data->region_code = $request->region['value'] : '';
            ($request->province) ? $data->province_code = $request->province['value'] : '';
            ($request->municipality) ? $data->municipality_code = $request->municipality['value'] : '';
            ($request->barangay) ? $data->barangay_code = $request->barangay['value'] : '';
            $data->save();
        }

        return [
            'data' => new DefaultResource($data),
            'message' => 'Campus updated successfully.', 
            'info' => "The campus details has been changed."
        ];
    }
}
