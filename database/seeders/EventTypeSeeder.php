<?php

namespace Database\Seeders;

use App\Models\EventType;
use Illuminate\Database\Seeder;

class EventTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EventType::create([
            'type' => 'Birthday Party',
        ]);

        EventType::create([
            'type' => 'Wedding Reception',
        ]);

        EventType::create([
            'type' => 'Debut / 18th Birthday',
        ]);

        EventType::create([
            'type' => 'Corporate Event',
        ]);

        EventType::create([
            'type' => 'Team Building',
        ]);

        EventType::create([
            'type' => 'Photo / Video Shoot',
        ]);

        EventType::create([
            'type' => 'Anniversary Celebration',
        ]);

        EventType::create([
            'type' => 'Other',
        ]);
    }
}
