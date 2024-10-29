<?php

namespace App\Services\Operation\Tagging;

use Carbon\Carbon;
use App\Models\TsrSample;
use App\Models\Configuration;
use App\Http\Resources\Operation\AnalysisResource;

class ViewClass
{
    public function __construct()
    {
        $this->laboratory = (\Auth::user()->myrole) ? \Auth::user()->myrole->laboratory_id : null;
        $this->configuration = Configuration::with('laboratory.address')->where('laboratory_id',$this->laboratory)->first();
    }

    public function lists($request){
        $pendings = []; $ongoings = []; $completeds = [];

        return [
            'pendings' => $this->pendings($request),
            'ongoings' => $this->ongoings($request),
            'completeds' => $this->completeds($request),
        ];
    }

    private function completeds($request){
        $laboratory = \Auth::user()->myrole->laboratory_type;
        $ongoings = TsrSample::when($request->keyword, function ($query, $keyword) {
            $query->where('code', 'LIKE', "%{$keyword}%");
        })->withWhereHas('tsr',function ($query) use ($laboratory,$request){
            $query->select('id','due_at','created_at');
            $query->where('laboratory_id',$this->laboratory)->where('laboratory_type',$laboratory)->whereIn('status_id',[3,4]);
            $query->when($request->reminder, function ($query, $reminder) {
                switch($reminder){
                    case 'Completed with no report number':
                        $query->where('status_id',4)->where('due_at','<',now())->where('released_at',null);
                    break;
                    case 'Due Soon':
                        $query->whereBetween('due_at', [Carbon::now()->startOfDay(), Carbon::now()->addDays(5)->endOfDay()]);
                    break;
                    case 'Overdue Request':
                        $query->where('status_id',3)->whereDate('due_at','<',now());
                    break;
                    case 'Completed':
                        $query->where('status_id',4);
                    break;
                }
            });
            $query->when($request->month, function ($query, $month) {
                $query->whereMonth('due_at',$month);
            });
        })->withWhereHas('analyses', function ($query) use ($request){
            $query->with('sample','testservice.testname','testservice.method.reference','testservice.method.method');
            $query->where('status_id',12);
            $query->when($request->reminder, function ($query, $reminder) {
                switch($reminder){
                    case 'Due Soon':
                        $query->whereIn('status_id',[10,11]);
                    break;
                    case 'Ongoing Task':
                        $query->where('analyst_id',\Auth::user()->id);
                    break;
                    case 'Completed':
                        $query->where('analyst_id',\Auth::user()->id);
                    break;
                }
            });
        })
        ->doesntHave('report')
        ->withCount([
            'analyses as analyses_count',
            'analyses as completed_analyses_count' => function ($query) {
                $query->where('status_id', 12);
            },
            'analyses as ongoing_analyses_count' => function ($query) {
                $query->where('status_id', 11);
            },
            'analyses as pending_analyses_count' => function ($query) {
                $query->where('status_id', 10);
            }
        ])
        ->get()->map(function ($sample) {
            return [
                'tsr_id' => $sample->tsr_id,
                'tsr' => $sample->tsr,
                'code' => $sample->code,
                'name' => $sample->name,
                'analyses' => AnalysisResource::collection($sample->analyses),
                'count' => $sample->analyses_count,
                'pending' => $sample->pending_analyses_count,
                'ongoing' => $sample->ongoing_analyses_count,
                'completed' => $sample->completed_analyses_count
            ];
        });
        return $ongoings;
    }   

    private function ongoings($request){
        $laboratory = \Auth::user()->myrole->laboratory_type;
        $ongoings = TsrSample::when($request->keyword, function ($query, $keyword) {
            $query->where('code', 'LIKE', "%{$keyword}%");
        })->withWhereHas('tsr',function ($query) use ($laboratory,$request){
            $query->select('id','due_at','created_at');
            $query->where('laboratory_id',$this->laboratory)->where('laboratory_type',$laboratory)->where('status_id',3);
            $query->when($request->month, function ($query, $month) {
                $query->whereMonth('due_at',$month);
            });
            $query->when($request->reminder, function ($query, $reminder) {
                switch($reminder){
                    case 'Completed with no report number':
                        $query->where('status_id',4)->where('due_at','<',now())->where('released_at',null);
                    break;
                    case 'Due Soon':
                        $query->whereBetween('due_at', [Carbon::now()->startOfDay(), Carbon::now()->addDays(5)->endOfDay()]);
                    break;
                    case 'Overdue Request':
                        $query->whereDate('due_at','<',now());
                    break;
                    case 'Completed':
                        $query->where('status_id',4);
                    break;
                }
            });
        })->withWhereHas('analyses', function ($query) use ($request){
            $query->with('sample','testservice.testname','testservice.method.reference','testservice.method.method');
            $query->where('status_id',11);
            $query->when($request->reminder, function ($query, $reminder) {
                switch($reminder){
                    case 'Due Soon':
                        $query->whereIn('status_id',[10,11]);
                    break;
                    case 'Ongoing Task':
                        $query->where('analyst_id',\Auth::user()->id);
                    break;
                    case 'Completed':
                        $query->where('analyst_id',\Auth::user()->id);
                    break;
                }
            });
        })
        ->withCount([
            'analyses as analyses_count',
            'analyses as completed_analyses_count' => function ($query) {
                $query->where('status_id', 12);
            },
            'analyses as ongoing_analyses_count' => function ($query) {
                $query->where('status_id', 11);
            },
            'analyses as pending_analyses_count' => function ($query) {
                $query->where('status_id', 10);
            }
        ])
        ->get()->map(function ($sample) {
            return [
                'tsr_id' => $sample->tsr_id,
                'tsr' => $sample->tsr,
                'code' => $sample->code,
                'name' => $sample->name,
                'analyses' => AnalysisResource::collection($sample->analyses),
                'count' => $sample->analyses_count,
                'pending' => $sample->pending_analyses_count,
                'ongoing' => $sample->ongoing_analyses_count,
                'completed' => $sample->completed_analyses_count
            ];
        });
        return $ongoings;
    }   

    private function pendings($request){
        $laboratory = \Auth::user()->myrole->laboratory_type;
        $pendings = TsrSample::when($request->keyword, function ($query, $keyword) {
            $query->where('code', 'LIKE', "%{$keyword}%");
        })->withWhereHas('tsr',function ($query) use ($laboratory,$request){
            $query->select('id','due_at','created_at');
            $query->where('laboratory_id',$this->laboratory)->where('laboratory_type',$laboratory)->where('status_id',3);
            $query->when($request->month, function ($query, $month) {
                $query->whereMonth('due_at',$month);
            });
            $query->when($request->reminder, function ($query, $reminder) {
                switch($reminder){
                    case 'Completed with no report number':
                        $query->where('status_id',4)->where('due_at','<',now())->where('released_at',null);
                    break;
                    case 'Due Soon':
                        $query->whereBetween('due_at', [Carbon::now()->startOfDay(), Carbon::now()->addDays(5)->endOfDay()]);
                    break;
                    case 'Overdue Request':
                        $query->whereDate('due_at','<',now());
                    break;
                    case 'Completed':
                        $query->where('status_id',4);
                    break;
                }
            });
        })->withWhereHas('analyses', function ($query) use ($request){
            $query->with('sample','testservice.testname','testservice.method.reference','testservice.method.method');
            $query->where('status_id',10);
            $query->when($request->reminder, function ($query, $reminder) {
                switch($reminder){
                    case 'Due Soon':
                        $query->whereIn('status_id',[10,11]);
                    break;
                    case 'Ongoing Task':
                        $query->where('analyst_id',\Auth::user()->id);
                    break;
                    case 'Completed':
                        $query->where('analyst_id',\Auth::user()->id);
                    break;
                }
            });
        })
        ->withCount([
            'analyses as analyses_count',
            'analyses as completed_analyses_count' => function ($query) {
                $query->where('status_id', 12);
            },
            'analyses as ongoing_analyses_count' => function ($query) {
                $query->where('status_id', 11);
            },
            'analyses as pending_analyses_count' => function ($query) {
                $query->where('status_id', 10);
            }
        ])
        ->get()->map(function ($sample) {
            return [
                'tsr_id' => $sample->tsr_id,
                'tsr' => $sample->tsr,
                'code' => $sample->code,
                'name' => $sample->name,
                'analyses' => AnalysisResource::collection($sample->analyses),
                'count' => $sample->analyses_count,
                'pending' => $sample->pending_analyses_count,
                'ongoing' => $sample->ongoing_analyses_count,
                'completed' => $sample->completed_analyses_count
            ];
        });
        return $pendings;
    }
}
