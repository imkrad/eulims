<?php

namespace App\Services\Operation\Quotation;

use Hashids\Hashids;
use App\Models\Configuration;
use App\Models\UserRole;
use App\Models\Quotation;
use App\Models\QuotationSample;
use App\Models\QuotationAnalysis;
use App\Http\Resources\Operation\QuotationResource;
use App\Http\Resources\Operation\Quotation\AnalysisResource;

class ViewClass
{
    public function __construct()
    {
        $this->laboratory = (\Auth::user()->userrole) ? \Auth::user()->myrole->laboratory_id : null;
    }

    public function counts($statuses){
        foreach($statuses as $status){
            $counts[] = Quotation::where('status_id',$status['value'])->count();
        }
        return $counts;
    }

    public function lists($request){
        $data = QuotationResource::collection(
            Quotation::query()
            ->with('service.service')
            ->with('createdby:id','createdby.profile:id,firstname,lastname,user_id')
            ->with('laboratory:id,name','type:id,name','status:id,name,color,others','discounted:id,name,value')
            ->with('customer:id,name_id,name,is_main','customer.customer_name:id,name,has_branches','customer.address:address,addressable_id,region_code,province_code,municipality_code,barangay_code','customer.address.region:code,name,region','customer.address.province:code,name','customer.address.municipality:code,name','customer.address.barangay:code,name')
            ->with('customer.contact:id,email,contact_no,customer_id')
            ->with('conforme:id,name,contact_no')
            ->when($request->keyword, function ($query, $keyword) {
                $query->where('code', 'LIKE', "%{$keyword}%")
                ->orWhereHas('customer',function ($query) use ($keyword) {
                    $query->whereHas('customer_name',function ($query) use ($keyword) {
                        $query->where('name', 'LIKE', "%{$keyword}%");
                    });
                });
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status_id',$status);
            })
            ->when($request->laboratory, function ($query, $laboratory) {
                $query->where('laboratory_type',$laboratory)->where('laboratory_id',$this->laboratory);
            })
            ->orderBy('created_at','DESC')
            ->paginate($request->count)
        );
        return $data;
    }

    public function view($id){
        $hashids = new Hashids('krad',10);
        $id = $hashids->decode($id);

        $data = new QuotationResource(
            Quotation::query()
            ->with('service.service')
            ->with('samples.analyses.testservice.testname','samples.analyses.addfee.service','samples.analyses.testservice.method.method','samples.analyses.testservice.method.reference')
            ->with('createdby:id','createdby.profile:id,firstname,lastname,user_id')
            ->with('laboratory:id,name','type:id,name','status:id,name,color,others','discounted:id,name,value')
            ->with('customer:id,name_id,name,is_main','customer.customer_name:id,name,has_branches','customer.address:address,addressable_id,region_code,province_code,municipality_code,barangay_code','customer.address.region:code,name,region','customer.address.province:code,name','customer.address.municipality:code,name','customer.address.barangay:code,name')
            ->with('customer.contact:id,email,contact_no,customer_id')
            ->with('conforme:id,name,contact_no')
            ->where('id',$id)->first()
        );
        return $data;
    }

    public function analyses($id){
        $hashids = new Hashids('krad',10);
        $id = $hashids->decode($id);

        $data = AnalysisResource::collection(
            QuotationAnalysis::query()
            ->with('sample')
            ->with('testservice.testname','testservice.method.method','testservice.method.reference')
            ->whereHas('sample',function ($query) use ($id){
                $query->whereHas('quotation',function ($query) use ($id){
                    $query->where('id',$id[0]);
                });
            })
            ->get()
        );
        return $data;
    }

    public function print($request){
        $hashids = new Hashids('krad',10);
        $id = $hashids->decode($request->id);

        $quotation = Quotation::query()
        ->with('service.service')
        ->with('createdby:id','createdby.profile:id,firstname,lastname,middlename,user_id')
        ->with('laboratory:id,name','status:id,name,color,others')
        ->with('customer:id,name_id,name,is_main','customer.customer_name:id,name,has_branches','customer.address:address,addressable_id,region_code,province_code,municipality_code,barangay_code','customer.address.region:code,name,region','customer.address.province:code,name','customer.address.municipality:code,name','customer.address.barangay:code,name')
        ->with('conforme:id,name,contact_no','customer.contact:id,email,contact_no,customer_id')
        ->where('id',$id[0])
        ->first();
      
        $samples = $this->analysesList($id[0]);

        $descs = QuotationSample::query()
        ->where('quotation_id',$id)
        ->get();

        $samples = QuotationSample::with('analyses.testservice.method.method','analyses.testservice.testname','analyses.addfee.service')->whereHas('quotation',function ($query) use ($id) {
            $query->where('id',$id);
        })->get();

        $groupedData = [];
        foreach ($samples as $row) {
            $sampleName = $row['name'];
            
            foreach($row['analyses'] as $index=>$analysis){
                $testName = $analysis['testservice']['testname']['name'];
                $testMethod = $analysis['testservice']['method']['method']['name'];
                $key = $sampleName. "_" . $analysis['sample_id'] . "_" . $testName . "_" . $testMethod;
                
                if (!isset($groupedData[$key])) {
                    if($analysis['addfee']){
                        $fee = [
                            'name' => $analysis['addfee']['service']['name'],
                            'fee' => $analysis['addfee']['service']['fee'],
                            'quantity' => $analysis['addfee']['quantity'],
                            'total' => $analysis['addfee']['total']
                        ];
                    }else{
                        $fee = null;
                    }
                    $groupedData[$key] = [
                        "samplename" => ($index == 0) ? $sampleName : '-',
                        "testname" => $testName,
                        "method" => $testMethod,
                        "count" => 0,
                        "fee" => $analysis['fee'],
                        'additional' => $fee
                    ];
                }
                $groupedData[$key]["count"] += 1;
            }
        }

        if(isset($quotation->service)){
            $service = [
                'name' => $quotation->service->service->name,
                'description' => $quotation->service->service->description,
                'quantity' => $quotation->service->quantity,
                'fee' => $quotation->service->fee
            ];
        }else{
            $service = null;
        }

        $samples2 = array_values($groupedData);

        $head = UserRole::with('user:id','user.profile:id,user_id,firstname,middlename,lastname')
        ->where('laboratory_id',$quotation->laboratory_id)->whereHas('role',function ($query){
            $query->where('name','Technical Manager');
        })
        ->where('laboratory_type',$quotation->laboratory_type)
        ->first();
        
        $array= [
            'configuration' => Configuration::first(),
            'quotation' => new QuotationResource($quotation),
            'samples' => $samples,
            'group' => $samples2,
            'service' => $service,
            'descs' => $descs,
            'manager' => $head->user->profile->firstname.' '.$head->user->profile->middlename[0].'. '.$head->user->profile->lastname,
            'user' => $quotation->createdby->profile->firstname.' '.$quotation->createdby->profile->middlename[0].'. '.$quotation->createdby->profile->lastname
        ]; 
        $pdf = \PDF::loadView('reports.quotation',$array)->setPaper('a4', 'portrait');
        return $pdf->stream('Quotation.pdf');
    }

    private function analysesList($id){
        $samples = QuotationAnalysis::query()->with('testservice.method.method','testservice.testname','sample')
        ->whereHas('sample',function ($query) use ($id) {
            $query->whereHas('quotation',function ($query) use ($id) {
                $query->where('id',$id);
            });
        })
        ->orderBy('created_at','ASC')
        ->get();

        $groupedData = [];
        foreach ($samples as $row) {
            $sampleName = $row['sample']['name'];
            $testName = $row['testservice']['testname']['name'];
            $testMethod = $row['testservice']['method']['method']['name'];
            $testMethodShort = $row['testservice']['method']['method']['short'];
            
            $key = $sampleName . "_" . $testName . "_" . $testMethod;
            
            if (!isset($groupedData[$key])) {
                $groupedData[$key] = [
                    "samplename" => $sampleName,
                    "testname" => $testName,
                    "method" => $testMethod,
                    "short" => $testMethodShort,
                    "count" => 0,
                    "fee" => $row['fee']
                ];
            }
            $groupedData[$key]["count"] += 1;
        }
        return $samples = array_values($groupedData);
    }
}
