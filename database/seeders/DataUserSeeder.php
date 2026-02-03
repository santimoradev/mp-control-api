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

      $rows = [
        [
          'email' => 'santimoradev@gmail.com',
          'username' => '0930519350',
          'password' => '123456',
          'first_name' => 'Admin',
          'last_name' => 'Dev',
          'role' => 1
        ],
        [
          'email' => 'smora@gmail.com',
          'username' => 'smora@gmail.com',
          'password' => '123456',
          'first_name' => 'Agente',
          'last_name' => 'Dev',
          'role' => 2
        ],
        [
          'email' => 'santiago@sandortech.com',
          'username' => 'santiago@sandortech.com',
          'password' => '123456',
          'first_name' => 'Manager',
          'last_name' => 'Dev',
          'role' => 3
        ],
        [
          'email' => 'santiagomora@hotmail.com',
          'username' => '0930519350',
          'password' => '123456',
          'first_name' => 'Staff',
          'last_name' => 'Dev',
          'role' => 4
        ]
      ];


      foreach ($rows as $row ) :
        $roleId = $row['role'];
        $role = Sentinel::findRoleById( $roleId );
        $dataUser = [
            'username' => $row['username'],
            'password' => $row['password'],
            'email' => $row['email'],
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'status' => 1
        ];
      $user = Sentinel::registerAndActivate($dataUser);
      if ( $role ) $user->roles()->attach( $role );
        $this->command->info('USER_ID='. $user->id . ' USERNAME='.$user->username);
      endforeach;
    }
}
