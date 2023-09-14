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
                'owner_id' => 1,
                'company_id' => 1,
                'name' => 'Uav 0',
                'charging_percentage' => 12,
                'position_json' => [
                    'x' => 37.874050,
                    'y' => 23.793520,
                ],
                'api_token' => null,
            ],
            [
                'owner_id' => 1,
                'company_id' => 1,
                'name' => 'Uav ASD',
                'charging_percentage' => 12,
                'position_json' => [
                    'x' => 37.874050,
                    'y' => 23.793520,
                ],
                'api_token' => null,
            ],
            [
                'owner_id' => 1,
                'company_id' => 1,
                'name' => 'Uav fsFS',
                'charging_percentage' => 12,
                'position_json' => [
                    'x' => 37.874050,
                    'y' => 23.793520,
                ],
                'api_token' => null,
            ],
            [
                'owner_id' => 1,
                'company_id' => 1,
                'name' => 'Uav GG',
                'charging_percentage' => 12,
                'position_json' => [
                    'x' => 37.874050,
                    'y' => 23.793520,
                ],
                'api_token' => null,
            ],
            [
                'owner_id' => 1,
                'company_id' => 1,
                'name' => 'Uav GGSD',
                'charging_percentage' => 12,
                'position_json' => [
                    'x' => 37.874050,
                    'y' => 23.793520,
                ],
                'api_token' => null,
            ],
            [
                'owner_id' => 1,
                'company_id' => 1,
                'name' => 'Uav HDHFD',
                'charging_percentage' => 12,
                'position_json' => [
                    'x' => 37.874050,
                    'y' => 23.793520,
                ],
                'api_token' => null,
            ],
            [
                'owner_id' => 1,
                'company_id' => 1,
                'name' => 'Uav SDJK',
                'charging_percentage' => 12,
                'position_json' => [
                    'x' => 37.874050,
                    'y' => 23.793520,
                ],
                'api_token' => null,
            ],
            [
                'owner_id' => 1,
                'company_id' => 1,
                'name' => 'Uav ASDLK:',
                'charging_percentage' => 12,
                'position_json' => [
                    'x' => 37.874050,
                    'y' => 23.793520,
                ],
                'api_token' => null,
            ],
            [
                'owner_id' => 1,
                'company_id' => 1,
                'name' => 'Uav QASDJKLASD',
                'charging_percentage' => 12,
                'position_json' => [
                    'x' => 37.874050,
                    'y' => 23.793520,
                ],
                'api_token' => null,
            ],
            [
                'owner_id' => 1,
                'company_id' => 1,
                'name' => 'Uav ASDKLJASJKL',
                'charging_percentage' => 12,
                'position_json' => [
                    'x' => 37.874050,
                    'y' => 23.793520,
                ],
                'api_token' => null,
            ],
            [
                'owner_id' => 1,
                'company_id' => 1,
                'name' => 'Uav LKLK',
                'charging_percentage' => 12,
                'position_json' => [
                    'x' => 37.874050,
                    'y' => 23.793520,
                ],
                'api_token' => null,
            ],
            [
                'owner_id' => 1,
                'company_id' => 1,
                'name' => 'Uav WEIUWUIE',
                'charging_percentage' => 12,
                'position_json' => [
                    'x' => 37.874050,
                    'y' => 23.793520,
                ],
                'api_token' => null,
            ],
            [
                'owner_id' => 1,
                'company_id' => 1,
                'name' => 'Uav IOUUIOUIO',
                'charging_percentage' => 1,
                'position_json' => [
                    'x' => 37.874050,
                    'y' => 23.793520,
                ],
                'api_token' => null,
            ],
            [
                'owner_id' => 2,
                'company_id' => 2,
                'name' => 'Uav 1',
                'charging_percentage' => 16,
                'position_json' => [
                    'x' => 37.874232,
                    'y' => 23.793155,
                ],
                'api_token' => null,
            ],
            [
                'owner_id' => 3,
                'company_id' => 3,
                'name' => 'Uav 2',
                'charging_percentage' => 77,
                'position_json' => [
                    'x' => 37.872132,
                    'y' => 23.793231,
                ],
                'api_token' => null,
            ],
            [
                'owner_id' => 3,
                'company_id' => 3,
                'name' => 'Uav 3',
                'charging_percentage' => 70,
                'position_json' => [
                    'x' => 37.872129,
                    'y' => 23.793230,
                ],
                'api_token' => null,
            ],
        ];

        foreach ($data as $v) {
            $uav = new Uav();

            $uav->owner_id = $v['owner_id'];
            $uav->company_id = $v['company_id'];
            $uav->name = $v['name'];
            $uav->charging_percentage = $v['charging_percentage'];
            $uav->position_json = $v['position_json'];

            $uav->save();
        }
    }
}
