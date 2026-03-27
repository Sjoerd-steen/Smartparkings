<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\ParkingSpot;
use App\Models\Reservation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder {
    public function run(): void {

        // === ADMIN ===
        $admin = User::create([
            'name'     => 'Admin SmartParking',
            'email'    => '[email protected]',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // === GEBRUIKERS ===
        $users = [
            ['name' => 'Sjerd de Kooning',  'email' => 'sjerd@smartparking.nl'],
            ['name' => 'Big Chungus',       'email' => 'bigchungus@smartparking.nl'],
            ['name' => 'Adem Karapinar',    'email' => 'adem@smartparking.nl'],
            ['name' => 'Testgebruiker',     'email' => 'user@smartparking.nl'],
        ];

        $createdUsers = [];
        foreach ($users as $userData) {
            $createdUsers[] = User::create([
                'name'     => $userData['name'],
                'email'    => $userData['email'],
                'password' => Hash::make('password'),
                'role'     => 'user',
            ]);
        }

        // === PARKEERPLAATSEN ===
        $spots = [];
        $verdiepingen = ['Begane grond', '1e Verdieping', '2e Verdieping'];
        $types = ['Standaard', 'Standaard', 'Standaard', 'Motor', 'Elektrisch'];
        $prijzen = [2.00, 2.50, 3.00, 1.50, 3.50];

        $letters = ['A','B','C','D'];
        foreach ($letters as $letter) {
            for ($i = 1; $i <= 6; $i++) {
                $idx = array_rand($types);
                $spots[] = ParkingSpot::create([
                    'name'           => "{$letter}{$i}",
                    'location'       => $verdiepingen[array_rand($verdiepingen)],
                    'status'         => $i <= 2 ? 'bezet' : ($i === 3 ? 'gereserveerd' : 'beschikbaar'),
                    'type'           => $types[$idx],
                    'price_per_hour' => $prijzen[$idx],
                ]);
            }
        }

        // Gehandicapt parkeerplaatsen
        for ($i = 1; $i <= 4; $i++) {
            $spots[] = ParkingSpot::create([
                'name'           => "G{$i}",
                'location'       => 'Begane grond',
                'status'         => 'beschikbaar',
                'type'           => 'Gehandicapt',
                'price_per_hour' => 1.00,
            ]);
        }

        // === RESERVERINGEN ===
        $betaalMethoden = ['ideal', 'paypal', 'tikkie', 'maestro'];

        foreach ($createdUsers as $user) {
            for ($r = 0; $r < rand(1, 3); $r++) {
                $spot = $spots[array_rand($spots)];
                $datum = now()->addDays(rand(-5, 10))->format('Y-m-d');
                $startUur = rand(7, 17);
                $eindUur  = $startUur + rand(1, 4);
                $uren     = $eindUur - $startUur;
                $prijs    = $uren * $spot->price_per_hour;

                Reservation::create([
                    'user_id'         => $user->id,
                    'parking_spot_id' => $spot->id,
                    'datum'           => $datum,
                    'start_tijd'      => sprintf('%02d:00', $startUur),
                    'eind_tijd'       => sprintf('%02d:00', $eindUur),
                    'voertuig'        => ['Auto','Motor','Fiets','Elektrisch'][rand(0,3)],
                    'kenteken'        => strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOP'), 0, 2)) . '-' . rand(100,999) . '-' . strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOP'), 0, 2)),
                    'totaal_prijs'    => $prijs,
                    'betaald'         => true,
                    'betaal_methode'  => $betaalMethoden[array_rand($betaalMethoden)],
                    'status'          => ['actief','voltooid'][rand(0,1)],
                ]);
            }
        }
    }
}
