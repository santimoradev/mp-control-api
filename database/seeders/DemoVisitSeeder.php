<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DemoVisitSeeder extends Seeder
{
    public function run(): void
    {

        $users = range(1,4);
        $products = range(1,14);
        $locations = range(200,300);

        $businessId = 1;

        $startPeriod = Carbon::create(null, 1, 1); // 1 enero
        $endPeriod = Carbon::now(); // hoy

        for ($r=1; $r<=12; $r++) {

            $routeStart = Carbon::createFromTimestamp(
                rand($startPeriod->timestamp, $endPeriod->timestamp)
            );

            $routeEnd = (clone $routeStart)->addDays(rand(3,7));

            $routeId = DB::table('routes')->insertGetId([
                'business_id' => $businessId,
                'created_by' => $users[array_rand($users)],
                'title' => 'Ruta Demo '.$r,
                'start_date' => $routeStart,
                'end_date' => $routeEnd,
                'status' => rand(2,3),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $visitsCount = rand(6,12);

            for ($v=0; $v<$visitsCount; $v++) {

                $scheduled = Carbon::createFromTimestamp(
                    rand($routeStart->timestamp, $routeEnd->timestamp)
                );

                $checkIn = (clone $scheduled)->addMinutes(rand(0,90));
                $checkOut = (clone $checkIn)->addMinutes(rand(10,40));

                $locationId = $locations[array_rand($locations)];
                $userId = $users[array_rand($users)];

                $visitId = DB::table('visits')->insertGetId([
                    'route_id' => $routeId,
                    'location_id' => $locationId,
                    'assigned_to' => $userId,
                    'scheduled_date' => $scheduled,
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'lat' => -12.04 + (rand(-100,100)/10000),
                    'lng' => -77.03 + (rand(-100,100)/10000),
                    'status' => rand(2,3),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                /*
                EXHIBITION
                */

                if(rand(0,1)) {

                    DB::table('exhibitions')->insert([
                        'name' => 'Exhibición detectada',
                        'source_id' => 1,
                        'visit_id' => $visitId,
                        'business_id' => $businessId,
                        'location_id' => $locationId,
                        'created_by' => $userId,
                        'before_description' => 'Exhibición inicial',
                        'after_description' => 'Se mejoró el orden',
                        'observed_at' => $checkIn,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                }

                /*
                ADITIONALS
                */

                for ($a=0; $a<rand(1,3); $a++) {

                    DB::table('aditionals')->insert([
                        'name' => 'Actividad detectada',
                        'source_id' => 1,
                        'visit_id' => $visitId,
                        'business_id' => $businessId,
                        'location_id' => $locationId,
                        'created_by' => $userId,
                        'type' => rand(1,3),
                        'description' => 'Promoción o pago registrado',
                        'observed_at' => $checkIn,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                }

                /*
                PRODUCT OBSERVATIONS
                */

                for ($o=0; $o<rand(2,5); $o++) {

                    DB::table('observations')->insert([
                        'visit_id' => $visitId,
                        'business_id' => $businessId,
                        'location_id' => $locationId,
                        'created_by' => $userId,
                        'product_id' => $products[array_rand($products)],
                        'price' => rand(5,25),
                        'stock' => rand(0,50),
                        'observed_at' => $checkIn,
                        'expiration_date' => now()->addMonths(rand(1,6)),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                }

            }

        }

    }
}
