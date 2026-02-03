<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Console\Command;
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
    $rows = [
      [
        'name' => 'Administrador',
        'slug' => 'admin',
        'permissions' => [
            'users' => true
        ]
      ],
      [
        'name' => 'Agente',
        'slug' => 'agent',
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
      $this->command->info('ROLE='. $row['name']);
    endforeach;
  }
}
