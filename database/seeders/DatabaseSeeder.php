<?php

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
    }

    public function UserSeeder(){
        $t = User::where('email','abc@gmail.com')->first();
        if(empty($t)){
            $user = User::create([
                'name' => 'demo',
                'email' => 'demo@hts.com.pk',
                'account_type' => 'admin',
                'logo' => '/place/1.png',
                'isactive' => 1,
                'password' => Hash::make('00000000'),
            ]);
        }
    }
}
