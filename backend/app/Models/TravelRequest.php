<?php

namespace App\Models;

use App\Enums\TravelStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TravelRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'requester_name',
        'destination',
        'departure_date',
        'return_date',
        'status',
    ];

    protected $casts = [
        'status' => TravelStatus::class,
    ];
    
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function isProcessed(): bool
    {
        return $this->status !== TravelStatus::SOLICITADO;
    }

}
