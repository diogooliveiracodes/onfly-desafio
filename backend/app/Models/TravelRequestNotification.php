<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelRequestNotification extends Model
{
    protected $fillable = [
        'travel_request_id',
        'user_id',
        'old_status',
        'new_status',
        'read_at',
    ];
}
