<?php

namespace App\Http\Controllers\Finance;

use App\Traits\HandlesTransaction;
use App\Services\DropdownClass;
use App\Services\Finance\OpClass;
use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\OpRequest;
use Illuminate\Http\Request;

class OpController extends Controller
{
    use HandlesTransaction;

    public function __construct(DropdownClass $dropdown, OpClass $op){
        $this->dropdown = $dropdown;
        $this->op = $op;
    }

    public function index(Request $request){
        switch($request->option){
            case 'lists':
                return $this->op->lists($request);
            break;
            case 'customers':
                return $this->op->customers($request);
            break;
            case 'tsrs':
                return $this->op->tsrs($request);
            break;
            case 'print':
                return $this->op->print($request);
            break;
            default :
            return inertia('Modules/Finance/Accounting/OrderPayments/Index',[
                'dropdowns' => [
                    'payments' => $this->dropdown->payment_modes(),
                    'statuses' => $this->dropdown->payment_statuses()
                ]
            ]);
        }
    }

    public function store(OpRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            switch($request->option){
                case 'op':
                    return $this->op->save($request);
                break;
                case 'delete':
                    return $this->op->delete($request);
                break;
            }
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }
}
