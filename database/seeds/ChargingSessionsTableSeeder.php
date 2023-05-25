<?php

use App\Uavsms\ChargingSession\ChargingSession;
use Illuminate\Database\Seeder;

class ChargingSessionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'charging_station_id' => 1,
                'uav_id' => 1,
                'date_time_start' => '2023-05-11 17:42:59',
                'date_time_end' => null,
                'estimated_date_time_end' => '2023-05-11 18:42:59',
                'kw_spent' => 0.10,
                'charging_session_cost_id' => 1,
            ],
            [
                'charging_station_id' => 2,
                'uav_id' => 2,
                'date_time_start' => '2023-05-11 17:28:59',
                'date_time_end' => null,
                'estimated_date_time_end' => '2023-05-11 18:28:59',
                'kw_spent' => 0.16,
                'charging_session_cost_id' => 2,
            ],
            [
                'charging_station_id' => 3,
                'uav_id' => 3,
                'date_time_start' => '2023-05-11 15:10:59',
                'date_time_end' => '2023-05-11 16:06:24',
                'estimated_date_time_end' => '2023-05-11 16:10:59',
                'kw_spent' => 0.20,
                'charging_session_cost_id' => 3,
            ],
        ];

        foreach ($data as $v) {
            $session = new ChargingSession();

            $session->charging_station_id = $v['charging_station_id'];
            $session->uav_id = $v['uav_id'];
            $session->date_time_start = $v['date_time_start'];
            $session->date_time_end = $v['date_time_end'];
            $session->estimated_date_time_end = $v['estimated_date_time_end'];
            $session->kw_spent = $v['kw_spent'];
            $session->charging_session_cost_id = $v['charging_session_cost_id'];

            $session->save();
        }
    }
}
