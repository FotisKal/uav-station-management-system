<?php

namespace App\Uavsms\ChargingSession;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChargingSessionCost extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'charging_session_id',
        'credits',
    ];
}
