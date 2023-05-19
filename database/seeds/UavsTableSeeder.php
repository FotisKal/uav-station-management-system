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
            ],
            [
                'owner_user_id' => 3,
                'name' => 'Uav 1',
            ],
            [
                'owner_user_id' => 4,
                'name' => 'Uav 2',
            ],
        ];

        foreach ($data as $v) {
            $uav = new Uav();

            $uav->owner_user_id = $v['owner_user_id'];
            $uav->name = $v['name'];

            $uav->save();
        }
    }
}
