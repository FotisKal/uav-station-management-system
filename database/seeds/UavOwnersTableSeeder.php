<?php

use App\Uavsms\UavOwner\UavOwner;
use Illuminate\Database\Seeder;

class UavOwnersTableSeeder extends Seeder
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
                'name' => 'Random Owner 1',
                'email' => 'random_owner_1@example.gr',
                'msisdn' => '306900000009',
            ],
            [
                'name' => 'Random Owner 2',
                'email' => 'random_owner_2@example.gr',
                'msisdn' => '306900000010',
            ],
            [
                'name' => 'Random Owner 3',
                'email' => 'random_owner_3@example.gr',
                'msisdn' => '306900000011',
            ],
        ];

        foreach ($data as $v) {
            $uav_owner = new UavOwner();
            $uav_owner->name = $v['name'];
            $uav_owner->email = $v['email'];
            $uav_owner->msisdn = $v['msisdn'];
            $uav_owner->save();
        }
    }
}
