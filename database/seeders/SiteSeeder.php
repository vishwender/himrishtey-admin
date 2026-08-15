<?php

namespace Database\Seeders;

use App\Models\Site;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    public function run(): void
    {
        $sites = [
            [
                'name' => 'Him Rishtey',
                'code' => 'main',
                'domain' => 'himrishtey.com',

                'database_host' => env('SITE_DB_HOST', 'db'),
                'database_port' => env('SITE_DB_PORT', '3306'),
                'database_name' => 'himrishtey_main',
                'database_username' => env('SITE_DB_USERNAME', 'db'),
                'database_password' => env('SITE_DB_PASSWORD', 'db'),

                'status' => true,
            ],

            [
                'name' => 'Gall Pakki',
                'code' => 'gallpakki',
                'domain' => 'gallpakki.com',

                'database_host' => env('SITE_DB_HOST', 'db'),
                'database_port' => env('SITE_DB_PORT', '3306'),
                'database_name' => 'himrishtey_gallpakki',
                'database_username' => env('SITE_DB_USERNAME', 'db'),
                'database_password' => env('SITE_DB_PASSWORD', 'db'),

                'status' => true,
            ],

            [
                'name' => 'Dev Bhoomi',
                'code' => 'devbhoomi',
                'domain' => 'devbhoomi.com',

                'database_host' => env('SITE_DB_HOST', 'db'),
                'database_port' => env('SITE_DB_PORT', '3306'),
                'database_name' => 'himrishtey_devbhoomi',
                'database_username' => env('SITE_DB_USERNAME', 'db'),
                'database_password' => env('SITE_DB_PASSWORD', 'db'),

                'status' => true,
            ],

            [
                'name' => 'Dogri Rishtey',
                'code' => 'dogririshtey',
                'domain' => 'dogririshtey.com',

                'database_host' => env('SITE_DB_HOST', 'db'),
                'database_port' => env('SITE_DB_PORT', '3306'),
                'database_name' => 'himrishtey_dogiririshtey',
                'database_username' => env('SITE_DB_USERNAME', 'db'),
                'database_password' => env('SITE_DB_PASSWORD', 'db'),

                'status' => true,
            ],
        ];

        foreach ($sites as $site) {
            Site::updateOrCreate(
                ['code' => $site['code']],
                $site
            );
        }

        $this->command->info('Three matrimonial sites seeded successfully.');
    }
}
