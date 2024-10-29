<?php

namespace App\Http\Controllers\Operation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Finance\AccountingClass;
use App\Services\Operation\Reports\AccomplishmentClass;

class ReportController extends Controller
{
    public function __construct(AccomplishmentClass $accomplishment, AccountingClass $accounting){
        $this->accomplishment = $accomplishment;
        $this->accounting = $accounting;
    }

    public function index(Request $request){
        switch($request->option){
            case 'lists':
                return [
                    'laboratories' => $this->accomplishment->laboratories($request)
                ];
            break;
            case 'pdf':
                return $this->accomplishment->pdf($request);
            break;
            case 'excel':
                return $this->accomplishment->excel($request);
            break;
            case 'accounting':
                return $this->accounting->report($request);
            break;
            default:
            if(\Auth::user()->myrole->role->name == 'Accountant'){
                return inertia('Modules/Finance/Accounting/Reports/Index',[
                    'years' => $this->accounting->years(),
                    'info' => [
                        'month' => \DateTime::createFromFormat('!m', date('m'))->format('F'),
                        'year' => date('Y')
                    ]
                ]);
            }else if(\Auth::user()->myrole->role->name == 'Cashier'){

            }else{
                return inertia('Modules/Operation/Reports/Index',[
                    'years' => $this->accomplishment->years(),
                    'types' => $this->accomplishment->laboratory_types(),
                    'info' => [
                        'month' => \DateTime::createFromFormat('!m', date('m'))->format('F'),
                        'year' => date('Y')
                    ]
                ]);
            }
        }
    }
}
