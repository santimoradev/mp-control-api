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

class DataUserUpdateSeeder extends Seeder
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
          'id' => 1,
          'email' => 'admin@mpcontrol.app',
          'username' => 'admin@mpcontrol.app',
          'password' => '123456Admin',
          'first_name' => 'Admin',
          'last_name' => 'Dev',
          'role' => 1
        ],
        [
          'id' => 2,
          'email' => 'agent@mpcontrol.app',
          'username' => 'agent@mpcontrol.app',
          'password' => '123456',
          'first_name' => 'Agente',
          'last_name' => 'Dev',
          'role' => 2
        ],
        [
          'id' => 3,
          'email' => 'manager@mpcontrol.app',
          'username' => 'manager@mpcontrol.app',
          'password' => '123456',
          'first_name' => 'Manager',
          'last_name' => 'Dev',
          'role' => 3
        ],
        [
          'id' => 4,
          'email' => 'staff@mpcontrol.app',
          'username' => '0930519350',
          'password' => '123456',
          'first_name' => 'Staff',
          'last_name' => 'Dev',
          'role' => 4
        ]
      ];


      foreach ($rows as $row ) :
        $user = Sentinel::findById($row['id']);
        $credentials = [
            'password' => $row['password']
        ];
        $user = Sentinel::update($user, $credentials);

        $this->command->info('USER_ID='. $user->id . ' USERNAME='.$user->username);
      endforeach;
    }
}
