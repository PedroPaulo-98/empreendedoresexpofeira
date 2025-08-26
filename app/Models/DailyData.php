<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Companies;

class DailyData extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'date',
        'daily_income',
        'future_income',
        'day_info',
    ];

    public function company()
    {
        return $this->belongsTo(Companies::class);
    }
}
