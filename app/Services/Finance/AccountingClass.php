<?php

namespace App\Services\Finance;

use App\Models\Target;
use App\Models\Configuration;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrExport;
use App\Exports\OpExport;

class AccountingClass
{
    public function __construct()
    {
        $this->laboratory = (\Auth::check()) ? (\Auth::user()->myrole) ? \Auth::user()->myrole->laboratory_id : null : '';
        $this->ids =(\Auth::check()) ? (\Auth::user()->role == 'Administrator') ? [] : json_decode(Configuration::where('laboratory_id',$this->laboratory)->value('laboratories')) : '';
    }

    public function years(){
        $data = Target::where('laboratory_id',$this->laboratory)->distinct()->pluck('year')->toArray();
        return $data;
    }

    public function report($request){
        switch($request->subtype){
            case 'pdf':
                $this->pdf($request);
            break;
            case 'excel':
                $this->excel($request);
            break;
        }
    }

    private function pdf($request){

    }

    private function excel($request){
        $month = ($request->month) ? \DateTime::createFromFormat('F', $request->month)->format('m') : date('m');  
        $year = ($request->year) ? $request->year : date('Y');

        return Excel::download(new OpExport($month,$year), 'op.xlsx');
    }
}
