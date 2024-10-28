<?php

namespace App\Services\Management;

use App\Models\School;
use App\Http\Resources\DefaultResource;

class SchoolClass
{   
    public function lists($request){
        $data = DefaultResource::collection(
            School::with('class')->withCount('campuses')
            ->when($request->keyword, function ($query, $keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%")->orWhere('shortcut', 'LIKE', "%{$keyword}%");
            })
            ->when($request->status, function ($query, $status) {
                $status = ($status == 'Active') ? 1 : 0;
                $query->where('is_active',$status);
            })
            ->when($request->class, function ($query, $class) {
                $query->where('class_id',$class);
            })
            ->orderBy('is_active','DESC')
            ->paginate($request->count)
        );
        return $data;
    }

    public function save($request){
        $data = School::create($request->all());
        if($data){
            foreach($request->campuses as $index => $campus){
                $c = $data->campuses()->create([
                    'campus' => $campus['name'],
                    'term_id' => $campus['term_id'],
                    'grading_id' => $campus['grading_id'],
                    'agency_id' => $campus['agency_id'],
                    'is_main' => $index === 0 ? 1 : 0
                ]);
                if($campus){
                    $address = $c->address()->create([
                        'address' => $campus['info'],
                        'barangay_code' => $campus['barangay']['value'],
                        'municipality_code' => $campus['municipality']['value'],
                        'province_code' => $campus['province']['value'],
                        'region_code' => $campus['region']['value']
                    ]);
                }
            }
            return [
                'data' => $data,
                'message' => 'School created successfully.', 
                'info' => 'The new school has been added.',
            ];
        }
    }

    public function view($request){
        $data = School::with('class')->withCount('campuses')
        ->with('campuses.address.region','campuses.address.province','campuses.address.municipality','campuses.address.barangay')
        ->with('campuses.grading','campuses.term','campuses.agency')
        ->where('id',$request->id)
        ->first();
        return $data;
    }

    public function notes(){
        return [
            [
                'name' => 'Ongoing Scholars',
                'description' => 'No. of scholars currently enrolled.',
                'count' => 0,
                'icon' => 'ri-error-warning-line',
                'color' => 'bg-info-subtle text-info'
            ],
            [
                'name' => 'Graduated Scholars',
                'description' => 'No. of scholars who graduated.',
                'count' => 1,
                'icon' => 'ri-error-warning-fill',
                'color' => 'bg-success-subtle text-success'
            ],
        ];
    }
}
