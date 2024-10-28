<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','others','is_sub','is_active','program_id'
    ];

    public function program()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'program_id', 'id');
    }
}
