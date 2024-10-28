<?php

namespace App\Observers;

use App\Models\Tsr;
use App\Models\TargetBreakdown;

class TsrObserver
{
    public function updated(Tsr $tsr): void
    {
        if ($tsr->isDirty('status_id') && $tsr->status_id == 2 || $tsr->status_id == 3) {
            $this->updateTargetBreakdown($tsr);
        }else if( $tsr->status_id == 5){
            $this->minusTargetBreakdown($tsr);
        }
    }

    private function minusTargetBreakdown(Tsr $tsr){
        $laboratory_id = $tsr->laboratory_id;
        $laboratory_type = $tsr->laboratory_type;
        $year = now()->year;

        $targetBreakdown = TargetBreakdown::where('name','Samples Received')->where('laboratory_type',$tsr->laboratory_type)
        ->whereHas('target',function ($query) use ($laboratory_id,$year) {
            $query->where('year',$year)->where('laboratory_id',$laboratory_id);
        })->first();

        if ($targetBreakdown) {
            $targetBreakdown->decerement('accom', $count);
        }

        $count = $tsr->samples()->withCount('analyses')->get()->sum('analyses_count');
        
        $targetBreakdown = TargetBreakdown::where('name','Services Conducted')->where('laboratory_type',$tsr->laboratory_type)
        ->whereHas('target',function ($query) use ($laboratory_id,$year) {
            $query->where('year',$year)->where('laboratory_id',$laboratory_id);
        })->first();

        if ($targetBreakdown) {
            $targetBreakdown->decrement('accom', $count);
        }
    }

    private function updateTargetBreakdown(Tsr $tsr)
    {
        $laboratory_id = $tsr->laboratory_id;
        $laboratory_type = $tsr->laboratory_type;
        $year = now()->year;

        $count = $tsr->samples()->count();
                    
        $targetBreakdown = TargetBreakdown::where('name','Samples Received')->where('laboratory_type',$tsr->laboratory_type)
        ->whereHas('target',function ($query) use ($laboratory_id,$year) {
            $query->where('year',$year)->where('laboratory_id',$laboratory_id);
        })->first();

        if ($targetBreakdown) {
            $targetBreakdown->increment('accom', $count);
        }

        $count = $tsr->samples()->withCount('analyses')->get()->sum('analyses_count');
        
        $targetBreakdown = TargetBreakdown::where('name','Services Conducted')->where('laboratory_type',$tsr->laboratory_type)
        ->whereHas('target',function ($query) use ($laboratory_id,$year) {
            $query->where('year',$year)->where('laboratory_id',$laboratory_id);
        })->first();

        if ($targetBreakdown) {
            $targetBreakdown->increment('accom', $count);
        }
    }
}
