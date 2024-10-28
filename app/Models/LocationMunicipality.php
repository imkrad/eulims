<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class LocationMunicipality extends Model
{
    use HasFactory, LogsActivity;
    
    public $timestamps = false;
    protected $primaryKey = 'code';
    protected $keyType = 'string';

    public function province()
    {
        return $this->belongsTo('App\Models\LocationProvince', 'province_code', 'code');
    }

    public function barangays()
    {
        return $this->hasMany('App\Models\LocationBarangay', 'municipality_code');
    } 

    protected static $recordEvents = ['updated','created'];
    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
        ->logOnly(['name','old_name','district','province_code'])
        ->setDescriptionForEvent(fn(string $eventName) => "$eventName the municipality details")
        ->useLogName('Municipality')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }
}
