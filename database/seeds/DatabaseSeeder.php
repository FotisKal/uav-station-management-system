<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ChargingCompaniesTableSeeder::class);
        $this->call(ChargingStationsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(UavOwnersTableSeeder::class);
        $this->call(UavsTableSeeder::class);
        $this->call(ChargingSessionCostsTableSeeder::class);
        $this->call(ChargingSessionsTableSeeder::class);
    }
}
