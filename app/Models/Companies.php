<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\DailyData;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Companies extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'cnpj_cpf',
        'phone',
        'address',
        'location',
        'information',
        'amount_of_employment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dailyData()
    {
        return $this->hasMany(DailyData::class, 'company_id');
    }
}
