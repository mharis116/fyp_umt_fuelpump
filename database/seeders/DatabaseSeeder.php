<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->UserSeeder();

        $this->call([
            // UserSeeder::class,
            ModuleSeeder::class,
        ]);
    }

    public function UserSeeder(){
        $t = User::where('email','demo@hts.com.pk')->first();
        if(empty($t)){
            $user = User::create([
                'name' => 'demo',
                'email' => 'demo@hts.com.pk',
                'account_type' => 'admin',
                'logo' => '/place/1.png',
                'isactive' => 1,
                'is_system' => 1,
                'password' => Hash::make('00000000'),
            ]);
        }
    }
}
