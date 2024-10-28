<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class SchoolAddress extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
       'address','region_code','province_code','municipality_code','barangay_code', 'campus_id'
    ];

    public function campus()
    {
        return $this->belongsTo('App\Models\SchoolCampus', 'campus_id', 'id');
    }

    public function region()
    {
        return $this->belongsTo('App\Models\LocationRegion', 'region_code', 'code');
    }

    public function province()
    {
        return $this->belongsTo('App\Models\LocationProvince', 'province_code', 'code');
    }

    public function municipality()
    {
        return $this->belongsTo('App\Models\LocationMunicipality', 'municipality_code', 'code');
    }

    public function barangay()
    {
        return $this->belongsTo('App\Models\LocationBarangay', 'barangay_code', 'code');
    }

    protected static $recordEvents = ['updated','created'];
    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
        ->logOnly(['address','region_code','province_code','municipality_code','barangay_code'])
        ->useLogName('Address')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }
}
