<?php

namespace App\Http\Controllers\Operation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\HandlesTransaction;
use App\Services\DropdownClass;
use App\Services\Operation\Quotation\ViewClass;
use App\Services\Operation\Quotation\SaveClass;
use App\Services\Operation\Quotation\UpdateClass;

class QuotationController extends Controller
{
    use HandlesTransaction;

    public function __construct(DropdownClass $dropdown, ViewClass $view, SaveClass $save, UpdateClass $update){
        $this->dropdown = $dropdown;
        $this->view = $view;
        $this->save = $save;
        $this->update = $update;
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
            return inertia('Modules/Operation/Quotations/Index',[
                'dropdowns' => [
                    'laboratories' => $this->dropdown->laboratory_types(),
                    'modes' => $this->dropdown->modes(),
                    'discounts' => $this->dropdown->discounts(),
                    'statuses' => $this->dropdown->statuses('Quotation')
                ],
                'counts' => $this->view->counts($this->dropdown->statuses('Quotation'))
            ]);
        }
    }

    public function store(Request $request){
        $result = $this->handleTransaction(function () use ($request) {
            switch($request->option){
                case 'quotation':
                    return $this->save->quotation($request);
                break;
                case 'sample':
                    return $this->save->sample($request);
                break;
                case 'analyses':
                    return $this->save->analyses($request);
                break;
                case 'service':
                    return $this->save->service($request);
                break;
                case 'fee':
                    return $this->save->fee($request);
                break;
                case 'tsr':
                    return $this->save->tsr($request);
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

    public function update(Request $request){
        $result = $this->handleTransaction(function () use ($request) {
            switch($request->option){
                case 'Confirm':
                    return $this->update->confirm($request);
                break;
                case 'Cancel':
                    return $this->update->cancel($request);
                break;
                case 'sample':
                    return $this->update->removeSample($request);
                break;
                case 'analysis':
                    return $this->update->removeAnalysis($request);
                break;
                case 'quotation':
                    return $this->update->quotationSample($request);
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

    public function show($id){
        return inertia('Modules/Operation/Quotations/Profile/Index',[
            'quotation' => $this->view->view($id),
            'analyses' => $this->view->analyses($id),
            'services' => $this->dropdown->services()
        ]);
    }
}
