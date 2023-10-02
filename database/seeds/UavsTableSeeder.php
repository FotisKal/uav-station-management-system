<?php

use App\Uavsms\Uav\Uav;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
                'charging_percentage' => 71,
                'position_json' => [
                    'x' => 37.899859,
                    'y' => 23.773511,
                ],
                'api_token' => Str::random(80),
            ],
            [
                'owner_id' => 1,
                'company_id' => 1,
                'name' => 'Uav 1',
                'charging_percentage' => 56,
                'position_json' => [
                    'x' => 37.871404,
                    'y' => 23.804207,
                ],
                'api_token' => Str::random(80),
            ],
            [
                'owner_id' => 2,
                'company_id' => 1,
                'name' => 'Uav 2',
                'charging_percentage' => 23,
                'position_json' => [
                    'x' => 37.925836,
                    'y' => 23.854012,
                ],
                'api_token' => Str::random(80),
            ],
            [
                'owner_id' => 4,
                'company_id' => 1,
                'name' => 'Uav 7',
                'charging_percentage' => 29,
                'position_json' => [
                    'x' => 37.856911,
                    'y' => 23.818686,
                ],
                'api_token' => Str::random(80),
            ],
            [
                'owner_id' => 6,
                'company_id' => 1,
                'name' => 'Uav 8',
                'charging_percentage' => 95,
                'position_json' => [
                    'x' => 37.892470,
                    'y' => 23.811314,
                ],
                'api_token' => Str::random(80),
            ],
            [
                'owner_id' => 7,
                'company_id' => 1,
                'name' => 'Uav 9',
                'charging_percentage' => 77,
                'position_json' => [
                    'x' => 37.908843,
                    'y' => 23.800492,
                ],
                'api_token' => Str::random(80),
            ],
            [
                'owner_id' => 8,
                'company_id' => 1,
                'name' => 'Uav 10',
                'charging_percentage' => 22,
                'position_json' => [
                    'x' => 37.936779,
                    'y' => 23.803291,
                ],
                'api_token' => Str::random(80),
            ],
            [
                'owner_id' => 9,
                'company_id' => 1,
                'name' => 'Uav 11',
                'charging_percentage' => 49,
                'position_json' => [
                    'x' => 37.912970,
                    'y' => 23.809211,
                ],
                'api_token' => Str::random(80),
            ],
            [
                'owner_id' => 10,
                'company_id' => 1,
                'name' => 'Uav 12',
                'charging_percentage' => 89,
                'position_json' => [
                    'x' => 37.915325,
                    'y' => 23.813580,
                ],
                'api_token' => Str::random(80),
            ],
            [
                'owner_id' => 11,
                'company_id' => 1,
                'name' => 'Uav 13',
                'charging_percentage' => 67,
                'position_json' => [
                    'x' => 37.880974,
                    'y' => 23.770566,
                ],
                'api_token' => Str::random(80),
            ],
            /* This one is being Charged right now. Id: 11 */
            [
                'owner_id' => 11,
                'company_id' => 1,
                'name' => 'Uav 14',
                'charging_percentage' => 14,
                'position_json' => [
                    'x' => 37.874132,
                    'y' => 23.793555,
                ],
                'api_token' => Str::random(80),
            ],
            [
                'owner_id' => 12,
                'company_id' => 1,
                'name' => 'Uav 15',
                'charging_percentage' => 56,
                'position_json' => [
                    'x' => 37.916364,
                    'y' => 23.811067,
                ],
                'api_token' => Str::random(80),
            ],
            [
                'owner_id' => 12,
                'company_id' => 1,
                'name' => 'Uav 16',
                'charging_percentage' => 59,
                'position_json' => [
                    'x' => 37.932194,
                    'y' => 23.817636,
                ],
                'api_token' => Str::random(80),
            ],
            [
                'owner_id' => 12,
                'company_id' => 1,
                'name' => 'Uav 17',
                'charging_percentage' => 66,
                'position_json' => [
                    'x' => 37.908213,
                    'y' => 23.789929,
                ],
                'api_token' => Str::random(80),
            ],
            [
                'owner_id' => 3,
                'company_id' => 1,
                'name' => 'Uav 18',
                'charging_percentage' => 29,
                'position_json' => [
                    'x' => 37.870246,
                    'y' => 23.782666,
                ],
                'api_token' => Str::random(80),
            ],
            [
                'owner_id' => 3,
                'company_id' => 1,
                'name' => 'Uav 19',
                'charging_percentage' => 29,
                'position_json' => [
                    'x' => 37.879296,
                    'y' => 23.801331,
                ],
                'api_token' => Str::random(80),
            ],
            [
                'owner_id' => 10,
                'company_id' => 1,
                'name' => 'Uav 20',
                'charging_percentage' => 82,
                'position_json' => [
                    'x' => 37.923950,
                    'y' => 23.791890,
                ],
                'api_token' => Str::random(80),
            ],



            /* This one is being Charged right now. Id: 18 */
            [
                'owner_id' => 3,
                'company_id' => 2,
                'name' => 'Uav 3',
                'charging_percentage' => 49,
                'position_json' => [
                    'x' => 38.166007,
                    'y' => 23.674957,
                ],
                'api_token' => Str::random(80),
            ],
            [
                'owner_id' => 4,
                'company_id' => 2,
                'name' => 'Uav 4',
                'charging_percentage' => 24,
                'position_json' => [
                    'x' => 38.146963,
                    'y' => 23.726260,
                ],
                'api_token' => Str::random(80),
            ],
            [
                'owner_id' => 5,
                'company_id' => 2,
                'name' => 'Uav 5',
                'charging_percentage' => 58,
                'position_json' => [
                    'x' => 38.155254,
                    'y' => 23.688453,
                ],
                'api_token' => Str::random(80),
            ],
            [
                'owner_id' => 2,
                'company_id' => 2,
                'name' => 'Uav 6',
                'charging_percentage' => 58,
                'position_json' => [
                    'x' => 38.155254,
                    'y' => 23.688453,
                ],
                'api_token' => Str::random(80),
            ],
            [
                'owner_id' => 11,
                'company_id' => 2,
                'name' => 'Uav 21',
                'charging_percentage' => 29,
                'position_json' => [
                    'x' => 38.147865,
                    'y' => 23.731599,
                ],
                'api_token' => Str::random(80),
            ],
            [
                'owner_id' => 11,
                'company_id' => 2,
                'name' => 'Uav 22',
                'charging_percentage' => 92,
                'position_json' => [
                    'x' => 38.153691,
                    'y' => 23.725542,
                ],
                'api_token' => Str::random(80),
            ],
            [
                'owner_id' => 12,
                'company_id' => 2,
                'name' => 'Uav 23',
                'charging_percentage' => 22,
                'position_json' => [
                    'x' => 38.159721,
                    'y' => 23.710246,
                ],
                'api_token' => Str::random(80),
            ],
            [
                'owner_id' => 13,
                'company_id' => 2,
                'name' => 'Uav 24',
                'charging_percentage' => 51,
                'position_json' => [
                    'x' => 38.169645,
                    'y' => 23.684543,
                ],
                'api_token' => Str::random(80),
            ],
            [
                'owner_id' => 14,
                'company_id' => 2,
                'name' => 'Uav 25',
                'charging_percentage' => 70,
                'position_json' => [
                    'x' => 38.172269,
                    'y' => 23.725112,
                ],
                'api_token' => Str::random(80),
            ],
            [
                'owner_id' => 14,
                'company_id' => 2,
                'name' => 'Uav 26',
                'charging_percentage' => 77,
                'position_json' => [
                    'x' => 38.152432,
                    'y' => 23.748169,
                ],
                'api_token' => Str::random(80),
            ],
            [
                'owner_id' => 15,
                'company_id' => 2,
                'name' => 'Uav 27',
                'charging_percentage' => 26,
                'position_json' => [
                    'x' => 38.161020,
                    'y' => 23.749176,
                ],
                'api_token' => Str::random(80),
            ],[
                'owner_id' => 16,
                'company_id' => 2,
                'name' => 'Uav 28',
                'charging_percentage' => 78,
                'position_json' => [
                    'x' => 38.175664,
                    'y' => 23.695499,
                ],
                'api_token' => Str::random(80),
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
