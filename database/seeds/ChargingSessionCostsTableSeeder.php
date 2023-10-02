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
        $data = [];

        for ($i = 0; $i < 204; $i++) {
            $data[] = [
                'credits' => 10.0,
            ];
        }

        foreach ($data as $v) {
            $cost = new ChargingSessionCost();

            $cost->credits = $v['credits'];

            $cost->save();
        }
    }
}
