<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TsrChild extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id','child_id'
     ];

    public function parent()
    {
        return $this->belongsTo('App\Models\Tsr', 'parent_id', 'id');
    }

    public function child()
    {
        return $this->belongsTo('App\Models\Tsr', 'child_id', 'id');
    }
}
