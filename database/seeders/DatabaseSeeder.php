<?php

namespace Database\Seeders;

use DB;
use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\BookSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //creation des types d'utilisateurs
        Type::factory()->create([
            'name' => 'user',
        ]);
        Type::factory()->create([
            'name' => 'admin',
        ]);
        Type::factory()->create([
            'name' => 'super admin',
        ]);

        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'type_id' => Type::where('name', 'super admin')->first()->id,
        ]);

        // Insertion des données de paramétrage
        DB::table('settings')->insert([
            'site_name' => 'ZeroLib',
            'contact_email' => 'hello@zerolib.org',
            'admin_email' => 'moafogaus@gmail.com',
            'adr_git' => 'https://github.com/zerolib',
            'adr_linkedin' => 'https://www.linkedin.com/company/zerolib/',
            'phone' => '+237658995265',
        ]);

        // On appelle notre BookSeeder
        $this->call([
            BookSeeder::class,
        ]);
    }
}
