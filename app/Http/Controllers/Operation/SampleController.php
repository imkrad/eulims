<?php

namespace App\Http\Controllers\Operation;

use App\Traits\HandlesTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Operation\Tsr\SampleClass;
use App\Http\Requests\Operation\SampleRequest;

class SampleController extends Controller
{
    use HandlesTransaction;

    public function __construct(SampleClass $sample){
        $this->sample = $sample;
    }

    public function index(Request $request){
        switch($request->option){
            case 'sampleqr':
                return $this->sample->qr($request);
            break;
            case 'allsampleqr':
                return $this->sample->allqr($request);
            break;
        }
    }

    public function store(SampleRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            switch($request->option){
                case 'remove':
                    return $this->sample->remove($request);
                break;
                case 'report':
                    return $this->sample->report($request);
                break;
                case 'disposal':
                    return $this->sample->disposal($request);
                break;
                default:
                return $this->sample->save($request);
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
            return $this->sample->update($request);
        });
        
        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }
}
