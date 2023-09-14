<?php

namespace App\Http\Controllers;

use App\Core\Utilities\MainMenu;
use App\Core\Utilities\Url;
use App\Uavsms\ChargingSession\ChargingSession;
use App\Uavsms\ChargingStation\ChargingStation;
use App\Uavsms\ChargingStation\PositionType;
use App\Uavsms\Uav\Uav;
use App\User;
use App\UserRole;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test(Request $request)
    {

    }
}
