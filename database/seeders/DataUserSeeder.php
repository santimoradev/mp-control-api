<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Console\Command;
use Sentinel as Sentinel;

use App\Imports\UserImport;

use App\Models\Business;
use App\Models\Area;
use App\Models\Profile;

class DataUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Model::unguard();

      DB::table('users')->truncate();
      DB::table('activations')->truncate();
      DB::table('reminders')->truncate();
      DB::table('persistences')->truncate();
      DB::table('role_users')->truncate();

      $rows = [
        [
          'username' => '0930519350',
          'password' => '0930519350',
          'first_name' => 'Webmaster',
          'last_name' => 'Dev',
          'role' => 1
        ],
        [
          'username' => '0987654321',
          'password' => '0987654321',
          'first_name' => 'John',
          'last_name' => 'Doe',
          'role' => 2
        ]
      ];


      foreach ($rows as $row ) :
        $roleId = $row['role'];
        $role = Sentinel::findRoleById( $roleId );
        $dataUser = [
            'username' => $row['username'],
            'password' => $row['password'],
            'email' => $row['username'].'@mktapp.com',
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'status' => 1
        ];
      $user = Sentinel::registerAndActivate($dataUser);
      if ( $role ) $user->roles()->attach( $role );
        $this->command->info('USER_ID='. $user->id . ' CI='.$user->username);
      endforeach;
    }
}
