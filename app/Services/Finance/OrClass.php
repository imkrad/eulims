<?php

namespace App\Services\Finance;

use App\Models\FinanceOp;
use App\Models\Configuration;
use App\Http\Resources\Finance\OrResource;

class OrClass
{
    public function __construct()
    {
        $this->laboratory = (\Auth::user()->myrole) ? \Auth::user()->myrole->laboratory_id : null;
        $this->configuration = Configuration::where('laboratory_id',$this->laboratory)->first();
    }
    
    public function lists($request){
        $data = OrResource::collection(
            FinanceOp::query()
            ->select('id','total','code','payment_id','collection_id','payorable_id','payorable_type','created_at','created_by')
            ->with('createdby:id','createdby.profile:user_id,firstname,lastname,middlename')
            ->with('payorable:id,name,name_id','payorable.customer_name:id,name')
            ->with('payment','collection')
            ->with(['items' => function ($query) {
                $query->with('itemable:id,code')->where('itemable_type', 'App\Models\Tsr');
            }, 'or:id,op_id,number','or.detail','or.wallet'])
            ->when($this->laboratory, function ($query, $lab) {
                $query->where('laboratory_id',$lab);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status_id',$status);
            })
            ->when($request->mode, function ($query, $mode) {
                $query->where('payment_id',$mode);
            })
            ->when($request->keyword, function ($query, $keyword) {
                $query->where('payorable_type', 'App\Models\Customer') // Only for 'Customer' types
                ->whereHas('payorable', function ($query) use ($keyword) {
                    $query->whereHas('customer_name', function ($query) use ($keyword) {
                        $query->where('name', 'like', '%' . $keyword . '%');
                    });
                });
            })
            ->where('payorable_type', 'App\Models\Customer')
            ->where('status_id',7)
            ->orderBy('updated_at','DESC')
            ->paginate($request->count)
        );
        // dd($data[0]);
        return $data;
    }
}
