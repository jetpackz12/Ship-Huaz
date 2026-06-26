<?php

namespace Database\Seeders;

use App\Models\ChatBotNode;
use Illuminate\Database\Seeder;

class ChatBotNodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ChatBotNode::create([
            'node_key' => 'Main',
            'message' => 'Ahoy! Welcome to Butal Ship Hauz. How can I help you today?',
        ]);

        ChatBotNode::create([
            'node_key' => 'Back to menu',
            'message' => 'What can I help you with?',
        ]);

        ChatBotNode::create([
            'node_key' => 'Venue Booking',
            'message' => 'Our iconic ship-shaped venue spaces start at ₱5,000 per event. We have 8 different event spaces for intimate dinners to grand receptions. Would you like to know more?',
        ]);

        ChatBotNode::create([
            'node_key' => 'Accommodations',
            'message' => 'Spend the night aboard the Ship Hauz! Our nautical-themed rooms start at ₱1,200/night. Enjoy stunning views of the Bohol landscape right from your room.',
        ]);

        ChatBotNode::create([
            'node_key' => 'Island Tours',
            'message' => 'Explore Northern Bohol natural wonders! We offer 12 island tour routes starting at ₱800/person. Hidden waterfalls, pristine beaches, and more await you.',
        ]);

        ChatBotNode::create([
            'node_key' => 'Tour Routes',
            'message' => 'We have 12 curated routes including island-hopping tours, waterfall treks, heritage site visits, and snorkeling adventures around Northern Bohol. All tours are guided and depart from Butal Ship Hauz.',
        ]);

        ChatBotNode::create([
            'node_key' => 'Event Planning',
            'message' => 'Let our crew handle everything! Event planning packages start at ₱15,000 and cover weddings, debuts, corporate events, and reunions — coordination, catering, and venue all included.',
        ]);

        ChatBotNode::create([
            'node_key' => 'Weddings at Ship Hauz',
            'message' => 'Say I do at Bohol most iconic venue! Our wedding packages include full catering, coordination, venue styling, and accommodation for the couple. Contact us for a custom quote tailored to your dream day.',
        ]);

        ChatBotNode::create([
            'node_key' => 'Dining & Catering',
            'message' => 'Savor fresh Bohol flavors at our on-site restaurant! Dining starts at ₱350/person. We also offer custom catering services for groups of all sizes — barkada, family reunions, corporate events.',
        ]);

        ChatBotNode::create([
            'node_key' => 'Photography Venue',
            'message' => 'The Ship Hauz is Bohol most photographed landmark! Photography sessions start at ₱2,500. Perfect for prenuptial shoots, fashion photography, debut pictorials, and commercial production.',
        ]);

        ChatBotNode::create([
            'node_key' => 'Location & Hours',
            'message' => 'Butal Ship Hauz, Talibon, Bohol, Philippines Open daily: 7:00 AM – 9:00 PM Reservations: 8:00 AM – 6:00 PM +63 (0) 912 345 6789',
        ]);

        ChatBotNode::create([
            'node_key' => 'Directions',
            'message' => 'Were located in Talibon, the gateway to Northern Bohol hidden gems. From Tagbilaran City, take a bus or van to Talibon (approx. 2–3 hours). Ask locals for Butal Ship Hauz — everyone knows it!',
        ]);

        ChatBotNode::create([
            'node_key' => 'Contact Us',
            'message' => 'Our crew is ready to help! +63 (0) 912 345 6789 reservations@butalshiphauz.com.ph For reservations and inquiries, we respond within 24 hours.',
        ]);

        ChatBotNode::create([
            'node_key' => 'Our Packages',
            'message' => 'Day Tour – ₱999 (venue access, 1 meal, guided tour) Overnight – ₱2,499 (room, 3 meals, island tour, venue access) Weekend – ₱4,299 (2 nights, all meals, 2 tours, event hall) Custom packages also available — contact us!',
        ]);

        ChatBotNode::create([
            'node_key' => 'How to Book',
            'message' => 'Booking is easy! 3 ways to reserve: 1️ Call us at +63 (0) 912 345 6789 2️ Email reservations@butalshiphauz.com.ph 3️ Use the Book a Reservation button on this page A 50% down payment is required to confirm bookings.',
        ]);
    }
}
