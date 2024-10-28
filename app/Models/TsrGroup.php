<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TsrGroup extends Model
{
    use HasFactory;

    protected $fillable = [
       'group','days','date','quantity','total','fee','tsr_id','testservice_id','status_id'
    ];

    public function testservice()
    {
        return $this->belongsTo('App\Models\Testservice', 'testservice_id', 'id');
    }

    public function getDateAttribute($value)
    {

        return ($value) ? date('F d, Y', strtotime($value)) : null;
    }

}
