<?php

use App\Uavsms\ChargingStation\ChargingStation;
use App\Uavsms\ChargingStation\PositionType;
use Illuminate\Database\Seeder;

class ChargingStationsTableSeeder extends Seeder
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
                'name' => 'Charging Station 1',
                'company_id' => '1',
                'position_type' => PositionType::AIR,
                'position_json' => [
                    'x' => 37.874132,
                    'y' => 23.793555,
                ],
            ],
            [
                'name' => 'Charging Station 2',
                'company_id' => '2',
                'position_type' => PositionType::GROUND,
                'position_json' => [
                    'x' => 37.921018,
                    'y' => 23.979330,
                ],
            ],
            [
                'name' => 'Charging Station 3',
                'company_id' => '3',
                'position_type' => PositionType::WATER,
                'position_json' => [
                    'x' => 38.049338,
                    'y' => 24.003171,
                ],
            ],
        ];

        foreach ($data as $v) {
            $station = new ChargingStation();

            $station->name = $v['name'];
            $station->company_id = $v['company_id'];
            $station->position_type = $v['position_type'];
            $station->position_json = $v['position_json'];

            $station->save();
        }
    }
}
