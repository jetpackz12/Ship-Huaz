<?php

namespace Database\Seeders;

use App\Models\PackageAddOn;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageAddOnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PackageAddOn::create([
            'title' => 'Captain Experience',
            'description' => 'Uniforms + immersive ship photo ops for all guests',
            'price' => '500',
        ]);

        PackageAddOn::create([
            'title' => 'Catering Package',
            'description' => 'Filipino-style buffet with drinks for your group',
            'price' => '3500',
        ]);

        PackageAddOn::create([
            'title' => 'Event Decoration',
            'description' => 'Themed ship décor, balloons, and table setup',
            'price' => '2000',
        ]);
        PackageAddOn::create([
            'title' => 'Photo & Video Coverage',
            'description' => 'Professional photographer for the duration of event',
            'price' => '4500',
        ]);

        PackageAddOn::create([
            'title' => 'Sound System',
            'description' => 'PA system with wireless microphone and playlist setup',
            'price' => '1500',
        ]);
    }
}
