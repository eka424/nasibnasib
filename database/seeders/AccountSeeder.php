<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['name' => 'Kas Tunai', 'type' => 'cash'],
            ['name' => 'Rekening Bank', 'type' => 'bank'],
            ['name' => 'QRIS', 'type' => 'qris'],
        ];

        foreach ($defaults as $a) {
            Account::firstOrCreate(['name' => $a['name']], $a);
        }
    }
}
