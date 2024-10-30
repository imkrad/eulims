<?php

namespace App\Services\Dashboard;

use App\Models\Tsr;
use App\Models\TsrPayment;
use App\Models\Configuration;
use App\Http\Resources\Finance\TsrNoPaymentResource;

class AccountantClass
{
    public function __construct()
    {
        $this->laboratory = (\Auth::check()) ? (\Auth::user()->myrole) ? \Auth::user()->myrole->laboratory_id : null : '';
        $this->ids =(\Auth::check()) ? (\Auth::user()->role == 'Administrator') ? [] : json_decode(Configuration::where('laboratory_id',$this->laboratory)->value('laboratories')) : '';
    }

    public function counts($request){
        return [
            $this->waiting($request),
            $this->pending($request),
            $this->total($request),
        ];
    }

    private function waiting($request){
        return $arr = [
            'name' => 'Order of Payment not Issued',
            'icon' => 'ri-bill-fill',
            'color' => 'danger',
            'total' => TsrPayment::whereHas('tsr',function ($query) use ($request){
                $query->where('laboratory_id',$this->laboratory)->whereIn('status_id',[3,4]);
            })
            ->where('is_free','!=',1)->where('is_paid','!=',1)->where('payment_id',null)->where('collection_id',null)->count()
        ];
    }

    private function total($request){
        return $arr = [
            'name' => 'Total Collection',
            'icon' => 'ri-hand-coin-fill',
            'color' => 'danger',
            'total' => TsrPayment::whereHas('tsr',function ($query) use ($request){
                $query->where('laboratory_id',$this->laboratory)->whereIn('status_id',[3,4]);
            })->where('status_id', 7)->where('is_paid',1)->sum('total')
        ];
    }

    private function pending($request){
        return $arr = [
            'name' => 'Pending Collection',
            'icon' => 'ri-safe-2-fill',
            'color' => 'danger',
            'total' => TsrPayment::whereHas('tsr',function ($query) use ($request){
                $query->where('laboratory_id',$this->laboratory);
            })->where('is_paid', 0)->where('is_free','!=',1)->sum('total')
        ];
    }

    public function forpayment($request){
        $data = TsrNoPaymentResource::collection(
            Tsr::query()
            ->with('customer:id,name_id,name,is_main','customer.customer_name:id,name,has_branches','customer.wallet')
            ->with('payment:tsr_id,id,total,subtotal,discount,or_number,is_paid,is_free,paid_at,status_id,discount_id,collection_id,payment_id','payment.status:id,name,color,others')
            ->when($request->keyword, function ($query, $keyword) {
                $query->where('code', 'LIKE', "%{$keyword}%")
                ->orWhereHas('customer',function ($query) use ($keyword) {
                    $query->whereHas('customer_name',function ($query) use ($keyword) {
                        $query->where('name', 'LIKE', "%{$keyword}%");
                    });
                });
            })
            ->when($this->laboratory, function ($query, $lab) {
                $query->where('laboratory_id',$lab);
            })
            ->whereHas('payment',function ($query){
                $query->where('payment_id',NULL)->where('collection_id',NULL);
            })
            ->where('status_id',2)
            ->get()
        );
        return $data;
    }
}
