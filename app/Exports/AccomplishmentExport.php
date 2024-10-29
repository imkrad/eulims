<?php

namespace App\Exports;

use App\Models\Tsr;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AccomplishmentExport implements FromView
{
    protected $month,$year,$lab;

    function __construct($month,$year,$lab) {
        $this->month = $month;
        $this->year = $year;
        $this->lab = $lab;
    }

    public function view(): View
    {
        $lists = Tsr::select('id','code','customer_id')
        ->whereDoesntHave('parent')
        ->with('customer:id,name,name_id','customer.customer_name:id,name','customer.address:address,addressable_id,region_code,province_code,municipality_code,barangay_code','customer.address.region:code,name,region','customer.address.province:code,name','customer.address.municipality:code,name','customer.address.barangay:code,name')
        
        ->with('payment.type')
        ->whereMonth('created_at',$this->month)
        ->whereYear('created_at',$this->year)
        ->where('laboratory_type',$this->lab)
        ->get();

        foreach ($lists as $row) {

            $address = ($row['customer']['address']['address'] != '' || $row['customer']['address']['address'] != NULL) ? $row['customer']['address']['address'].', ' : '';
            if ($row['customer']['address']['municipality']['name'] == 'Zamboanga City' || $row['customer']['address']['municipality']['name'] == 'Isabela City') {
                $a = '';
            } else {
                $a = ', '.$row['customer']['address']['province']['name'];
            }

            $complete_address = $address.$row['customer']['address']['barangay']['name'].', '.$row['customer']['address']['municipality']['name'].$a;

            $code = $row['code'];
            $customer = $row['customer']['customer_name']['name'];
            $address = $complete_address;
            $gratis =  ($row['payment']['is_free']) ? $row['payment']['discount'] : '-';


            $tsrs[] = [
                "code" => $code,
                "customer" => $customer,
                "address" => $complete_address,
                "ornumber" => $row['payment']['or_number'],
                "subtotal" => $row['payment']['subtotal'],
                "discount" => ($row['payment']['is_free']) ? '-' : $row['payment']['discount'],
                "type" => ( $row['payment']['type']) ? $row['payment']['type']['name'] : '-',
                "total" => $row['payment']['total'],
                "gratis" => $gratis,
                "date" =>  $row['payment']['paid_at'],
            ];
        }


        return view('exports.accomplishment', [
            'lists' => $tsrs
        ]);
    }
}
