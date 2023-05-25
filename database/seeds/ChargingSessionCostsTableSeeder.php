<?php

use App\Uavsms\ChargingSession\ChargingSessionCost;
use Illuminate\Database\Seeder;

class ChargingSessionCostsTableSeeder extends Seeder
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
                'credits' => 10.0,
            ],
            [
                'credits' => 6.6,
            ],
            [
                'credits' => 9.3,
            ],
        ];

        foreach ($data as $v) {
            $cost = new ChargingSessionCost();

            $cost->credits = $v['credits'];

            $cost->save();
        }
    }
}
