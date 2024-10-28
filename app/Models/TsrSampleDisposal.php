<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TsrSampleDisposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'disposed_at',
        'disposal_id',
        'sample_id',
        'user_id'
    ];

    public function disposal()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'disposal_id', 'id');
    }

    public function sample()
    {
        return $this->belongsTo('App\Models\TsrSample', 'sample_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function getDisposedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }

}
