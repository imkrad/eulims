<?php

namespace App\Http\Controllers\Operation;

use App\Traits\HandlesTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DropdownClass;
use App\Services\Operation\Tsr\ViewClass;
use App\Services\Operation\Tsr\SaveClass;
use App\Http\Requests\Operation\TsrRequest;

class TsrController extends Controller
{
    use HandlesTransaction;

    public function __construct(DropdownClass $dropdown, SaveClass $save, ViewClass $view){
        $this->dropdown = $dropdown;
        $this->save = $save;
        $this->view = $view;
    }

    public function index(Request $request){
        switch($request->option){
            case 'lists':
                return $this->view->lists($request);
            break;
            case 'print':
                return $this->view->print($request);
            break;
            default :
            return inertia('Modules/Operation/Tsrs/Index',[
                'dropdowns' => [
                    'laboratories' => $this->dropdown->laboratory_types(),
                    'discounts' => $this->dropdown->discounts(),
                    'statuses' => $this->dropdown->statuses('Request'),
                    'services' => $this->dropdown->services(),
                    'purposes' => $this->dropdown->purposes()
                ],
                'counts' => $this->view->counts($this->dropdown->statuses('Request'))
            ]);
        }
    }

    public function show($id){
        return inertia('Modules/Operation/Tsrs/Profile/Index',[
            'tsr' => $this->view->view($id),
            'analyses' => $this->view->analyses($id),
            'services' => $this->dropdown->services(),
            'laboratories' => $this->dropdown->laboratory_types()
        ]);
    }

    public function store(TsrRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            return $this->save->save($request);
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }
    
}
