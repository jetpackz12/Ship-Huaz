<?php

namespace Database\Seeders;

use App\Models\PaymentOption;
use Illuminate\Database\Seeder;

class PaymentOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentOption::create([
            'payment' => 'GCash',
            'number' => '987654321',
            'account' => 'Butal Ship Hauz',
            'description' => 'After sending, fill in your details below so we can verify your payment.',
        ]);

        PaymentOption::create([
            'payment' => 'Maya',
            'number' => '987654321',
            'account' => 'Butal Ship Hauz',
            'description' => 'After sending, fill in your details below so we can verify your payment.',
        ]);
    }
}
