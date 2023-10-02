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
                'credits' => '1000',
            ],
            [
                'name' => 'Random Owner 2',
                'email' => 'random_owner_2@example.gr',
                'msisdn' => '306900000010',
                'credits' => '1000',
            ],
            [
                'name' => 'Random Owner 3',
                'email' => 'random_owner_3@example.gr',
                'msisdn' => '306900000011',
                'credits' => '1000',
            ],
            [
                'name' => 'Random Owner 4',
                'email' => 'random_owner_4@example.gr',
                'msisdn' => '306900000011',
                'credits' => '1000',
            ],
            [
                'name' => 'Random Owner 5',
                'email' => 'random_owner_5@example.gr',
                'msisdn' => '306900000011',
                'credits' => '1000',
            ],
            [
                'name' => 'Random Owner 6',
                'email' => 'random_owner_6@example.gr',
                'msisdn' => '306900000011',
                'credits' => '1000',
            ],
            [
                'name' => 'Random Owner 7',
                'email' => 'random_owner_7@example.gr',
                'msisdn' => '306900000011',
                'credits' => '1000',
            ],
            [
                'name' => 'Random Owner 8',
                'email' => 'random_owner_8@example.gr',
                'msisdn' => '306900000011',
                'credits' => '1000',
            ],
            [
                'name' => 'Random Owner 9',
                'email' => 'random_owner_9@example.gr',
                'msisdn' => '306900000011',
                'credits' => '1000',
            ],
            [
                'name' => 'Random Owner 10',
                'email' => 'random_owner_10@example.gr',
                'msisdn' => '306900000011',
                'credits' => '1000',
            ],
            [
                'name' => 'Random Owner 11',
                'email' => 'random_owner_11@example.gr',
                'msisdn' => '306900000011',
                'credits' => '1000',
            ],
            [
                'name' => 'Random Owner 12',
                'email' => 'random_owner_12@example.gr',
                'msisdn' => '306900000011',
                'credits' => '1000',
            ],
            [
                'name' => 'Random Owner 13',
                'email' => 'random_owner_13@example.gr',
                'msisdn' => '306900000011',
                'credits' => '1000',
            ],
            [
                'name' => 'Random Owner 14',
                'email' => 'random_owner_14@example.gr',
                'msisdn' => '306900000011',
                'credits' => '1000',
            ],
            [
                'name' => 'Random Owner 15',
                'email' => 'random_owner_15@example.gr',
                'msisdn' => '306900000011',
                'credits' => '1000',
            ],
            [
                'name' => 'Random Owner 16',
                'email' => 'random_owner_16@example.gr',
                'msisdn' => '306900000011',
                'credits' => '1000',
            ],

        ];

        foreach ($data as $v) {
            $uav_owner = new UavOwner();
            $uav_owner->name = $v['name'];
            $uav_owner->email = $v['email'];
            $uav_owner->msisdn = $v['msisdn'];
            $uav_owner->credits = $v['credits'];
            $uav_owner->save();
        }
    }
}
