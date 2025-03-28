<?php
namespace Database\Seeders;

use App\Models\User;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $superAdmin = User::create([
            'name'     => 'Super Admin',
            'email'    => 'superAdmin@ltssoftware.nl',
            'password' => bcrypt("password"),

        ]);

        // $this->call(ShieldSeeder::class);
        // $this->command->call('shield:generate', ['--panel' => 'Admin', '--all' => 'true']);
        // $this->command->call('shield:super-admin', ['--user' => $superAdmin->id, '--panel' => 'Admin']);
        $this->call([
            UserSeeder::class,
            CompanySeeder::class,
            CompanyUserSeeder::class,
            ObjectTypeSeeder::class,
            WorkActivitieSeeder::class,

        ]);

    }
}
