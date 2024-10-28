<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ListCourse extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name','shortcut','abbreviation','others','is_active'
    ];

    protected static $recordEvents = ['updated','created'];
    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->logOnly(['name','shortcut','abbreviation','others','is_active'])
            ->useLogName('Course')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs(); 
    }
}
