<?php

namespace Database\Seeders;

use App\Models\VenuePackage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VenuePackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VenuePackage::create([
            'title' => 'Upper Deck Experience',
            'description' => 'Open-air upper deck with panoramic views. Complimentary coffee/juice included.',
            'guests' => 30,
            'price' => 3500,
            'status' => 'active',
        ]);

        VenuePackage::create([
            'title' => 'Main Deck Venue',
            'description' => 'Spacious main deck area perfect for celebrations and gatherings.',
            'guests' => 80,
            'price' => 6500,
            'status' => 'active',
        ]);

        VenuePackage::create([
            'title' => 'Full Ship Exclusive',
            'description' => 'Exclusive use of the entire Butal Ship Hauz — all decks, all areas.',
            'guests' => 150,
            'price' => 12000,
            'status' => 'active',
        ]);

        VenuePackage::create([
            'title' => 'Overnight Stay Package',
            'description' => 'Private overnight booking for intimate gatherings or special celebrations.',
            'guests' => 20,
            'price' => 18000,
            'status' => 'active',
        ]);
    }
}
