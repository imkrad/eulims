<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListIndustry extends Model
{
    use HasFactory;

    public function customer_industry()
    {
        return $this->hasMany('App\Models\Customer', 'industry_id');
    } 
}
