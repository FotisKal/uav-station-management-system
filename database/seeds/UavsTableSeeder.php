<?php

use App\Uavsms\Uav\Uav;
use Illuminate\Database\Seeder;

class UavsTableSeeder extends Seeder
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
                'owner_user_id' => 2,
                'name' => 'Uav 0',
                'charging_percentage' => '12%',
                'position_json' => [
                    'x' => 37.874050,
                    'y' => 23.793520,
                ],
            ],
            [
                'owner_user_id' => 3,
                'name' => 'Uav 1',
                'charging_percentage' => '16%',
                'position_json' => [
                    'x' => 37.874232,
                    'y' => 23.793155,
                ],
            ],
            [
                'owner_user_id' => 4,
                'name' => 'Uav 2',
                'charging_percentage' => '77%',
                'position_json' => [
                    'x' => 37.872132,
                    'y' => 23.793231,
                ],
            ],
        ];

        foreach ($data as $v) {
            $uav = new Uav();

            $uav->owner_user_id = $v['owner_user_id'];
            $uav->name = $v['name'];
            $uav->charging_percentage = $v['charging_percentage'];
            $uav->position_json = $v['position_json'];

            $uav->save();
        }
    }
}
