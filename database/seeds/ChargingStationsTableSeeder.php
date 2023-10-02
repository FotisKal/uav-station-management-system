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
                'name' => 'Glyfada Stavraetos Station',
                'company_id' => '1',
                'position_type' => PositionType::AIR,
                'position_json' => [
                    'x' => 37.874132,
                    'y' => 23.793555,
                ],
            ],
            [
                'name' => 'Markopoulo Mesogaias Spilia Drikou Station',
                'company_id' => '1',
                'position_type' => PositionType::GROUND,
                'position_json' => [
                    'x' => 37.921018,
                    'y' => 23.979330,
                ],
            ],
            [
                'name' => 'Glyfada Terpsithea Station',
                'company_id' => '1',
                'position_type' => PositionType::GROUND,
                'position_json' => [
                    'x' => 37.901060,
                    'y' => 23.779389,
                ],
            ],
            [
                'name' => 'Glyfada Profitis Ilias Station',
                'company_id' => '1',
                'position_type' => PositionType::GROUND,
                'position_json' => [
                    'x' => 37.881101,
                    'y' => 23.782443,
                ],
            ],
            [
                'name' => 'Glyfada Parko Skylon Station',
                'company_id' => '1',
                'position_type' => PositionType::GROUND,
                'position_json' => [
                    'x' => 37.862886,
                    'y' => 23.772577,
                ],
            ],
            [
                'name' => 'Vari Pesonton Pyrosveston Station',
                'company_id' => '1',
                'position_type' => PositionType::GROUND,
                'position_json' => [
                    'x' => 37.859991,
                    'y' => 23.796449,
                ],
            ],
            [
                'name' => 'Kropia Spilaio Saligaros Station',
                'company_id' => '1',
                'position_type' => PositionType::GROUND,
                'position_json' => [
                    'x' => 37.864876,
                    'y' => 23.809830,
                ],
            ],
            [
                'name' => 'Kropia Ekpaideytiria Kaisari Station',
                'company_id' => '1',
                'position_type' => PositionType::GROUND,
                'position_json' => [
                    'x' => 37.867542,
                    'y' => 23.820156,
                ],
            ],
            [
                'name' => 'Kropia Pedio Airsoft Station',
                'company_id' => '1',
                'position_type' => PositionType::GROUND,
                'position_json' => [
                    'x' => 37.886828,
                    'y' => 23.820961,
                ],
            ],
            [
                'name' => 'Kropia Mavrovouni Ymitou Station',
                'company_id' => '1',
                'position_type' => PositionType::GROUND,
                'position_json' => [
                    'x' => 37.898939,
                    'y' => 23.807556,
                ],
            ],
            [
                'name' => 'Glyfada Spilia Ntaveli Ymitou Station',
                'company_id' => '1',
                'position_type' => PositionType::GROUND,
                'position_json' => [
                    'x' => 37.910701,
                    'y' => 23.799349,
                ],
            ],
            [
                'name' => 'Ilioupoli Oropedio Sesi Station',
                'company_id' => '1',
                'position_type' => PositionType::GROUND,
                'position_json' => [
                    'x' => 37.920364,
                    'y' => 23.793069,
                ],
            ],




            [
                'name' => 'Parnitha Agios Nikolaos Station',
                'company_id' => '2',
                'position_type' => PositionType::GROUND,
                'position_json' => [
                    'x' => 38.136729,
                    'y' => 23.734035,
                ],
            ],
            [
                'name' => 'Parnitha Thea Station',
                'company_id' => '2',
                'position_type' => PositionType::GROUND,
                'position_json' => [
                    'x' => 38.140300,
                    'y' => 23.714571,
                ],
            ],
            [
                'name' => 'Parnitha Agios Georgios Station',
                'company_id' => '2',
                'position_type' => PositionType::GROUND,
                'position_json' => [
                    'x' => 38.137125,
                    'y' => 23.692588,
                ],
            ],
            [
                'name' => 'Parnitha Proponitirio Segas Station',
                'company_id' => '2',
                'position_type' => PositionType::GROUND,
                'position_json' => [
                    'x' => 38.146569,
                    'y' => 23.714024,
                ],
            ],
            [
                'name' => 'Parnitha Casino Station',
                'company_id' => '2',
                'position_type' => PositionType::GROUND,
                'position_json' => [
                    'x' => 38.152776,
                    'y' => 23.735136,
                ],
            ],
            [
                'name' => 'Parnitha Mnimeio Sarakatsanaion Station',
                'company_id' => '2',
                'position_type' => PositionType::GROUND,
                'position_json' => [
                    'x' => 38.159008,
                    'y' => 23.728309,
                ],
            ],
            [
                'name' => 'Parnitha Mpafi Station',
                'company_id' => '2',
                'position_type' => PositionType::GROUND,
                'position_json' => [
                    'x' => 38.166899,
                    'y' => 23.726746,
                ],
            ],
            [
                'name' => 'Parnitha Flampouri Station',
                'company_id' => '2',
                'position_type' => PositionType::GROUND,
                'position_json' => [
                    'x' => 38.161677,
                    'y' => 23.746173,
                ],
            ],
            [
                'name' => 'Parnitha Pigi Mesiano Nero Station',
                'company_id' => '2',
                'position_type' => PositionType::GROUND,
                'position_json' => [
                    'x' => 38.174864,
                    'y' => 23.742256,
                ],
            ],
            [
                'name' => 'Parnitha Pigi Platana Station',
                'company_id' => '2',
                'position_type' => PositionType::GROUND,
                'position_json' => [
                    'x' => 38.171845,
                    'y' => 23.699635,
                ],
            ],
            [
                'name' => 'Parnitha Ethnikos Drimos Station',
                'company_id' => '2',
                'position_type' => PositionType::GROUND,
                'position_json' => [
                    'x' => 38.166007,
                    'y' => 23.674957,
                ],
            ],
            [
                'name' => 'Parnitha Parko Psyhon Station',
                'company_id' => '2',
                'position_type' => PositionType::GROUND,
                'position_json' => [
                    'x' => 38.149732,
                    'y' => 23.719581,
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
