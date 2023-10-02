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
        $station_ids = [];
        $uav_ids = [];
        $data = [];


        for ($i = 1; $i < 13; $i++) {
            $station_ids[] = $i;
        }

        for ($i = 1; $i < 18; $i++) {
            $uav_ids[] = $i;
        }

        $hour_number = 11;
        $cost_id = 1;

        foreach ($station_ids as $station_id) {
            $counter = 0;
            $day_number = 1;
            $month_number = 1;

            foreach ($uav_ids as $uav_id) {
                if ($counter != 0) {
                    if ($counter % 2 == 0) {
                        $day_number++;
                        $month_number++;
                        $hour_number = 11;
                    }
                }

                $rand_minutes_start = rand(0, 59);
                $rand_seconds_start = rand(0, 59);

                $rand_minutes_end = rand(0, 59);
                $rand_seconds_end = rand(0, 59);

                $datetime_start = '2023-0' . $month_number . '-0' . $day_number . ' ' . $hour_number . ':' . $rand_minutes_start . ':' .  $rand_seconds_start;
                $datetime_end = '2023-0' . $month_number . '-0' . $day_number . ' ' . ++$hour_number . ':' . $rand_minutes_end . ':' . $rand_seconds_end;

                $data[] = [
                    'charging_station_id' => $station_id,
                    'uav_id' => $uav_id,
                    'date_time_start' => $datetime_start,
                    'date_time_end' => $datetime_end,
                    'estimated_date_time_end' => null,
                    'kw_spent' => 0.10,
                    'charging_session_cost_id' => $cost_id,
                ];

                $hour_number++;
                $counter++;
                $cost_id++;
            }
        }

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
