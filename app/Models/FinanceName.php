<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceName extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_individual'
    ];

    public function payorable()
    {
        return $this->morphOne('App\Models\FinanceOp', 'payorable');
    }
}
