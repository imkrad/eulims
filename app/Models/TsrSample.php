<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TsrSample extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'customer_description',
        'description',
        'is_disposed',
        'is_completed',
        'tsr_id',
    ];

    public function tsr()
    {
        return $this->belongsTo('App\Models\Tsr', 'tsr_id', 'id');
    }

    public function report()
    {
        return $this->hasOne('App\Models\TsrSampleReport', 'sample_id');
    }

    public function disposal()
    {
        return $this->hasOne('App\Models\TsrSampleDisposal', 'sample_id');
    }

    public function analyses()
    {
        return $this->hasMany('App\Models\TsrAnalysis', 'sample_id');
    }
    
    public function getUpdatedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }

    public function getCreatedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
    }
}
