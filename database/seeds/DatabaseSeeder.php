<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        //Create 10 customers with 10 transactions
        factory(\App\Customer::class, 10)
            ->create()
            ->each(function($customer){
                factory(\App\Transaction::class, 10)->create([
                   'customer_id' => $customer->id
                ]);
            });

    }
}
