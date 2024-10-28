<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ListStatus extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name', 'type', 'color', 'others', 'is_active'
    ];

    protected static $recordEvents = ['updated','created'];
    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->logOnly(['name', 'type', 'short', 'regular_amount', 'summer_amount', 'is_fixed', 'is_active', 'is_reimburseable'])
            ->useLogName('Privilege')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs(); 
    }
}
