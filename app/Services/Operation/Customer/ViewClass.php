<?php

namespace App\Services\Operation\Customer;

use Hashids\Hashids;
use App\Models\Wallet;
use App\Models\Tsr;
use App\Models\TsrPayment;
use App\Models\Customer;
use App\Models\CustomerName;
use App\Models\Configuration;
use App\Http\Resources\Operation\Customer\TsrResource;
use App\Http\Resources\Operation\Customer\CustomerResource;

class ViewClass
{
    public $laboratory, $configuration;

    public function __construct()
    {
        $this->laboratory = (\Auth::user()->myrole) ? \Auth::user()->myrole->laboratory_id : null;
        $this->configuration = Configuration::with('laboratory.address')->where('laboratory_id',$this->laboratory)->first();
    }

    public function lists($request){
        $data = CustomerResource::collection(
            Customer::query()
            ->with('customer_name:id,name','classification:id,name','industry:id,name,industry_id,is_main,is_alone,is_active')
            ->with('address.region:code,name,region','address.province:code,name','address.municipality:code,name','address.barangay:code,name')
            ->when($request->keyword, function ($query, $keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%")
                ->orWhereHas('customer_name',function ($query) use ($keyword) {
                    $query->where('name', 'LIKE', "%{$keyword}%");
                });
            })
            ->where('laboratory_id',$this->laboratory)
            ->orderBy('created_at','desc')
            ->paginate($request->count)
        );
        return $data;
    }

    public function view($id){
        $hashids = new Hashids('krad',10);
        $id = $hashids->decode($id);

        $data = new CustomerResource(
            Customer::query()
            ->with('wallet.transactions.receipt')
            ->with('conformes')
            ->with('customer_name:id,name','classification:id,name','industry:id,name')
            ->with('address.region:code,name,region','address.province:code,name','address.municipality:code,name','address.barangay:code,name')
            ->where('id',$id)->first()
        );
        return $data;
    }

    public function search($request){
        $keyword = $request->keyword;
        $data = CustomerName::where('name', 'LIKE', "%{$keyword}%")->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name,
                'has_branches' => $item->has_branches
            ];
        });
        return $data;
    }

    public function pick($request){
        $keyword = $request->keyword;
        $id = $request->id;
        $data = Customer::with('conformes')->with('customer_name')
        ->where(function($query) use ($keyword,$id) {
            $query->where('name', 'LIKE', "%{$keyword}%")
                ->where('id','!=',$id)
                ->orWhereHas('customer_name', function ($query) use ($keyword,$id) {
                    $query->where('name', 'LIKE', "%$keyword%")->whereHas('customer', function ($query) use ($id) {
                        $query->where('id','!=',$id);
                    });
                });
        })
        ->get()->map(function ($item) {
            $name = ($item->customer_name->has_branches) ? ($item->is_main) ? $item->customer_name->name :  $item->customer_name->name.' - '.$item->name : $item->customer_name->name;
            return [
                'value' => $item->id,
                'name' => $name,
                'conformes' => $item->conformes->map(function ($i) {
                    return [
                        'value' => $i->id,
                        'name' => $i->name,
                        'contact_no' => $i->contact_no
                    ];
                })
            ];
        });
        if($keyword){
            return $data;
        }else{
            return [];
        }
    }


    public function counts($request){
        $id = $request->id;
        $wallet = Wallet::where('customer_id',$id)->value('available');
        $tsrs = Tsr::whereIn('status_id',[2,3,4])->where('customer_id',$request->id)->count();
        $total = TsrPayment::where('is_paid',1)->where('status_id',7)
        ->whereHas('tsr',function ($query) use ($id){
            $query->where('customer_id',$id);
        })->sum('total');
        $array = [
            ['counts' => $tsrs, 'name' => 'Total Request', 'icon' => 'ri-list-check-2', 'color' => 'success'],
            ['counts' => '₱'.number_format($total,2,'.',','),'name' => 'Total Spending', 'icon' => 'ri-hand-coin-fill', 'color' => 'info'],
            ['counts' => ($wallet) ? $wallet : '₱0.00','name' => 'My Wallet', 'icon' => 'ri-wallet-3-fill', 'color' => 'primary'],
        ];
        return $array;
    }

    public function tsrs($request){
        $data = TsrResource::collection(
            Tsr::query()
            ->select('id','code','status_id','created_at')
            ->with('status')
            ->with('payment:tsr_id,id,total,is_paid,is_free,status_id','payment.status:id,name,color,others')
            ->when($request->keyword, function ($query, $keyword) {
                $query->where('code', 'LIKE', "%{$keyword}%");
            })
            ->when($this->laboratory, function ($query, $lab) {
                $query->where('laboratory_id',$lab);
            })
            ->where('customer_id',$request->id)
            ->orderBy('created_at','DESC')
            ->paginate($request->count)
        );
        return $data;
    }

    public function region(){
        return $this->configuration->laboratory->address->region_code;
    }
}
