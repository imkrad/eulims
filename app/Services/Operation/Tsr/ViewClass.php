<?php

namespace App\Services\Operation\Tsr;

use App\Models\Tsr;
use App\Models\UserRole;
use App\Models\Configuration;
use App\Http\Resources\Operation\TsrResource;

class ViewClass
{
    public function __construct()
    {
        $this->laboratory = (\Auth::user()->userrole) ? \Auth::user()->userrole->laboratory_id : null;
        $this->configuration = Configuration::with('laboratory.address')->where('laboratory_id',$this->laboratory)->first();
        $data = UserRole::where('user_id',\Auth::user()->id)->pluck('laboratory_type');
        $filteredData = $data->filter(function ($value) {
            return !is_null($value);
        });
        $this->type = $filteredData->isNotEmpty() ? $filteredData : null;
    }

    public function counts($statuses){
        foreach($statuses as $status){
            $counts[] = Tsr::where('status_id',$status['value'])->count();
        }
        return $counts;
    }

    public function lists($request){
        $data = TsrResource::collection(
            Tsr::query()
            ->with('customer:id,name_id,name,is_main','customer.customer_name:id,name,has_branches','customer.wallet')
            ->with('customer.address:address,addressable_id,region_code,province_code,municipality_code,barangay_code','customer.address.region:code,name,region','customer.address.province:code,name','customer.address.municipality:code,name','customer.address.barangay:code,name')
            ->with('payment:tsr_id,id,total,subtotal,discount,or_number,is_paid,is_free,paid_at,status_id,discount_id,collection_id,payment_id','payment.status:id,name,color,others')
            ->when($request->keyword, function ($query, $keyword) {
                $query->where('code', 'LIKE', "%{$keyword}%")
                ->orWhereHas('customer',function ($query) use ($keyword) {
                    $query->whereHas('customer_name',function ($query) use ($keyword) {
                        $query->where('name', 'LIKE', "%{$keyword}%");
                    });
                });
            })
            ->with(['samples' => function ($query){
                $query->select('id','tsr_id');
                $query->withCount([
                    'analyses as analyses_count',
                    'analyses as completed_analyses_count' => function ($query) {
                        $query->where('status_id', 12);
                    },
                    'analyses as ongoing_analyses_count' => function ($query) {
                        $query->where('status_id', 11);
                    }
                ]);
            }])
            ->when($request->status, function ($query, $status) {
                $query->where('status_id',$status);
            })
            ->when($request->datetype && $request->date, function ($query) use ($request) {
                $query->whereDate($request->datetype, $request->date);
            })
            ->when($this->laboratory, function ($query, $lab) {
                $query->where('laboratory_id',$lab);
            })
            ->when($request->laboratory , function ($query, $labtype ) {
                $query->where('laboratory_type',$labtype );
            }) 
            ->when($request->sort, function ($query, $sort) use ($request) {
                if($request->sortby == 'Code'){
                    $query->orderBy('code',$request->sort);
                }else if($request->sortby == 'Requested At'){
                    $query->orderBy('created_at',$request->sort);
                }else{
                    $query->orderBy('due_at',$request->sort);
                }
            })
            ->when($request->reminder, function ($query, $reminder) {
                switch($reminder){
                    case 'Due Soon':
                        $query->whereBetween('due_at', [Carbon::now()->startOfDay(), Carbon::now()->addDays(5)->endOfDay()])->where('status_id','!=',4);
                    break;
                    case 'Overdue Request':
                        $query->where('status_id',3)->whereDate('due_at','<',now());
                    break;
                    case 'For Released':
                        $query->where('status_id',4)->where('due_at','>',now())->where('released_at',null)->where('laboratory_id',$this->laboratory) ->whereHas('samples', function ($query) {
                            $query->doesntHave('report');
                        }, '=', 0);
                    break;
                    case 'Unclaimed Reports':
                        $query->where('status_id',4)->where('due_at','<=', now()->subDays(30))->where('released_at',null)->where('laboratory_id',$this->laboratory)->whereHas('samples', function ($query) {
                            $query->doesntHave('report');
                        }, '=', 0);
                    break;
                }
            })
            ->paginate($request->count)
        );
        return $data;
    }
}
