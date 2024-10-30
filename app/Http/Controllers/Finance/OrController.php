<?php

namespace App\Http\Controllers\Finance;

use App\Traits\HandlesTransaction;
use App\Services\DropdownClass;
use App\Services\Finance\OrClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrController extends Controller
{
    use HandlesTransaction;

    public function __construct(DropdownClass $dropdown, OrClass $or){
        $this->dropdown = $dropdown;
        $this->or = $or;
    }

    public function index(Request $request){
        switch($request->option){
            case 'lists':
                return $this->or->lists($request);
            break;
            default :
            return inertia('Modules/Finance/Cashiering/Receipts/Index',[
                'dropdowns' => [
                    'payments' => $this->dropdown->payment_modes(),
                    'statuses' => $this->dropdown->payment_statuses()
                ]
            ]);
        }
    }
}
