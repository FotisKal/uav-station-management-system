<?php

use App\User;
use App\Core\Api\Token;
use Illuminate\Database\Seeder;
use App\Core\Utilities\DateFormat;
use App\Core\Utilities\DatetimeFormat;

class UsersTableSeeder extends Seeder
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
            'role_id' => '1',
            'name' => 'Fotis Kal',
            'email' => 'f.kalokiris@example.gr',
            'msisdn' => '306900000005',
            'email_verified_at' => date('Y-m-d H:i:s'),
            'password' => 'demo1234',
            'date_format' => DateFormat::$formats[0],
            'datetime_format' => DatetimeFormat::$formats[0],
            'api_token' => 'T0k8pHr9QYgC9vA8iBPIxeNff5QvJ1',
            'debug' => true,
            ],
            [
                'role_id' => '100',
                'name' => 'Random Uav Owner',
                'email' => 'r.uav.user_0@example.gr',
                'msisdn' => '306900000006',
                'email_verified_at' => date('Y-m-d H:i:s'),
                'password' => 'demo1234',
                'date_format' => DateFormat::$formats[0],
                'datetime_format' => DatetimeFormat::$formats[0],
                'api_token' => 'T0k8pHr9QYgC9vA8iBPIxeNff5QvJ1',
                'debug' => true,
            ],
            [
                'role_id' => '100',
                'name' => 'Random Uav Owner 2',
                'email' => 'r.uav.user_1@example.gr',
                'msisdn' => '306900000007',
                'email_verified_at' => date('Y-m-d H:i:s'),
                'password' => 'demo1234',
                'date_format' => DateFormat::$formats[0],
                'datetime_format' => DatetimeFormat::$formats[0],
                'api_token' => 'T0k8pHr9QYgC9vA8iBPIxeNff5QvJ1',
                'debug' => true,
            ],
            [
                'role_id' => '100',
                'name' => 'Random Uav Owner 3',
                'email' => 'r.uav.user_2@example.gr',
                'msisdn' => '306900000008',
                'email_verified_at' => date('Y-m-d H:i:s'),
                'password' => 'demo1234',
                'date_format' => DateFormat::$formats[0],
                'datetime_format' => DatetimeFormat::$formats[0],
                'api_token' => 'T0k8pHr9QYgC9vA8iBPIxeNff5QvJ1',
                'debug' => true,
            ],
        ];

        foreach ($data as $v) {
            $user = new User();
            $user->role_id = $v['role_id'];
            $user->name = $v['name'];
            $user->email = $v['email'];
            $user->msisdn = $v['msisdn'];
            $user->email_verified_at = $v['email_verified_at'];
            $user->password = Hash::make($v['password']);
            $user->date_format = $v['date_format'];
            $user->datetime_format = $v['datetime_format'];
            $user->debug = $v['debug'];
            $user->save();

            $token = new Token();
            $token->user_id = $user->id;
            $token->token = $v['api_token'];
            $token->last_used = date('Y-m-d H:i:s');
            $token->expires = date('Y-m-d H:i:s');
            $token->save();
        }
    }
}
