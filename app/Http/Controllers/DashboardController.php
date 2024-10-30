<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DropdownClass;
use App\Services\Dashboard\AccountantClass;

class DashboardController extends Controller
{
    public function __construct(DropdownClass $dropdown, AccountantClass $accountant){
        $this->dropdown = $dropdown;
        $this->accountant = $accountant;
    }

    public function index(Request $request){
        if(!\Auth::check()){
            return inertia('Auth/Login');
        }else{
            if(\Auth::user()->role === 'Administrator'){
                return inertia('Modules/Executive/Dashboard/Index');
            }else{
                $role = \Auth::user()->myrole->role->name;
                switch($role){
                    case 'Accountant':
                        return inertia('Modules/Finance/Accounting/Dashboard/Index',[
                            'dropdowns' => [
                                'counts' => $this->accountant->counts($request),
                                'collections' => $this->dropdown->collections('Laboratory'),
                                'payments' => $this->dropdown->payment_modes(),
                                'tsrs' => $this->accountant->forpayment($request),
                            ]
                        ]);
                    break;
                    default: 
                    return inertia('Modules/Operation/Dashboard/CRO/Index');
                }
                
            }
        }
    }

    public function search(Request $request){
        $option = $request->option;
        switch($option){
            case 'provinces':
                return $this->dropdown->provinces($request->code);
            break;
            case 'municipalities':
                return $this->dropdown->municipalities($request->code);
            break;
            case 'barangays':
                return $this->dropdown->barangays($request->code);
            break;
        }
    }
}
