<?php

use App\Uavsms\ChargingCompany\ChargingCompany;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChargingCompaniesTableSeeder extends Seeder
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
                'name' => 'Fast Charging',
            ],
            [
                'name' => 'Electronic Stations',
            ],
        ];

        foreach ($data as $v) {
            $company = new ChargingCompany();

            $company->name = $v['name'];

            $company->save();
        }
    }
}
