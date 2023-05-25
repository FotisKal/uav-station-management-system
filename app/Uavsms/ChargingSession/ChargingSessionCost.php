<?php

namespace App\Uavsms\ChargingSession;

use Illuminate\Database\Eloquent\Model;

class ChargingSessionCost extends Model
{
    protected $fillable = [
        'charging_session_id',
        'credits',
    ];
}
