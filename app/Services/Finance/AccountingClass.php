<?php

namespace App\Services\Finance;

use App\Models\Target;
use App\Models\Configuration;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RSTLExport;
use App\Exports\OpExport;
use App\Exports\ReconciliationExport;

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
                return $this->pdf($request);
            break;
            case 'excel':
                return $this->excel($request);
            break;
        }
    }

    private function pdf($request){
        $month = ($request->month) ? \DateTime::createFromFormat('F', $request->month)->format('m') : date('m');  
        $year = ($request->year) ? $request->year : date('Y');
        
        if($request->type == 'op'){

        }else if($request->type == 'rstl'){

        }else{

        }
    }

    private function excel($request){
        $month = ($request->month) ? \DateTime::createFromFormat('F', $request->month)->format('m') : date('m');  
        $year = ($request->year) ? $request->year : date('Y');

        if($request->type == 'op'){
            return Excel::download(new OpExport($month,$year), 'opor.xlsx');
        }else if($request->type == 'rstl'){
            return Excel::download(new RSTLExport($month,$year), 'rstl.xlsx');
        }else{
            return Excel::download(new ReconciliationExport($month,$year), 'reconciliation.xlsx');
        }
    }
}
