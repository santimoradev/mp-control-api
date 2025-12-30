<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Sentinel as Sentinel;

class DataRoleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Model::unguard();
    DB::table('roles')->truncate();
    DB::table('role_users')->truncate();
    DB::table('throttle')->truncate();
    $rows = [
      [
        'name' => 'Administrador',
        'slug' => 'admin',
        'permissions' => [
            'users' => true
        ]
      ],
      [
        'name' => 'Manager',
        'slug' => 'manager',
        'permissions' => [
            'users' => false
        ]
      ],
      [
        'name' => 'Agente',
        'slug' => 'agent',
        'permissions' => [
            'users' => false
        ]
      ],
      [
        'name' => 'Staff',
        'slug' => 'staff',
        'permissions' => [
            'users' => false
        ]
      ]
    ];
    foreach( $rows as $row ) :
      $row = Sentinel::getRoleRepository()->createModel()->create([
        'name' => $row['name'],
        'slug' => $row['slug'],
        'permissions' => $row['permissions']
      ]);
    endforeach;
  }
}
