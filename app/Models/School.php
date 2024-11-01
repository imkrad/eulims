<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'class_id',
        'shortcut',
        'combiner',
        'avatar',
        'is_upsystem',
        'is_active'
    ];

    public function campuses()
    {
        return $this->hasMany('App\Models\SchoolCampus', 'school_id');
    } 

    public function class()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'class_id', 'id');
    }

    public function getUpdatedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }

    public function getCreatedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }
}
