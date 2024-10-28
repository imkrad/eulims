<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class LocationBarangay extends Model
{
    use HasFactory, LogsActivity;

    public $timestamps = false;
    protected $primaryKey = 'code';
    protected $keyType = 'string';

    public function municipality()
    {
        return $this->belongsTo('App\Models\LocationMunicipality', 'municipality_code', 'code');
    }

    protected static $recordEvents = ['updated','created'];
    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
        ->logOnly(['name','old_name','district','municipality_code'])
        ->setDescriptionForEvent(fn(string $eventName) => "$eventName the barangay details")
        ->useLogName('Barangay')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }
}
