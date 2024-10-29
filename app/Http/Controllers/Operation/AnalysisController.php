<?php

namespace App\Http\Controllers\Operation;

use App\Traits\HandlesTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Operation\Tsr\AnalysisClass;
use App\Http\Requests\Operation\AnalysisRequest;


class AnalysisController extends Controller
{
    use HandlesTransaction;

    public function __construct(AnalysisClass $analysis){
        $this->analysis = $analysis;
    }

    public function store(AnalysisRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            switch($request->option){
                case 'service':
                    return $this->analysis->service($request);
                break;
                case 'fee':
                    return $this->analysis->fee($request);
                break;
                case 'remove':
                    return $this->analysis->remove($request);
                break;
                case 'group':
                    return $this->analysis->group($request);
                break;
                default:
                    return $this->analysis->save($request);
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
