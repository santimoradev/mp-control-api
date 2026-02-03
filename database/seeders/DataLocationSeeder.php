<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Console\Command;
use Sentinel as Sentinel;

use App\Imports\UserImport;

use App\Models\Province;
use App\Models\City;

class DataLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Model::unguard();
      $rows = Province::all();

      foreach ( $rows as $row ) :
        $row->name = trim( $row->name );
        $row->save();
        $this->command->info('ID='. $row->id . ' Name='.$row->name);
      endforeach;
      $rows = City::all();
      foreach ( $rows as $row ) :
        $row->name = trim( $row->name );
        $row->save();
        $this->command->info('ID='. $row->id . ' Name='.$row->name);
      endforeach;
    }
}
