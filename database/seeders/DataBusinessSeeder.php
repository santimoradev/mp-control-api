<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Console\Command;

use App\Models\Business;

class DataBusinessSeeder extends Seeder
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
          'name' => 'Proalco S.A.',
          'status' => 1
        ],
      ];


      foreach ($rows as $row ) :
        $business = Business::create($row);
        $this->command->info('BUSINESS='. $business->name );
      endforeach;
    }
}
