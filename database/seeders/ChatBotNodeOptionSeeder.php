<?php

namespace Database\Seeders;

use App\Models\ChatBotNode;
use App\Models\ChatBotNodeOption;
use Illuminate\Database\Seeder;

class ChatBotNodeOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    { // Wipe existing data cleanly
        ChatBotNodeOption::truncate();
        ChatBotNode::truncate();

        // ── 1. Seed all nodes ──────────────────────────────────────────────
        $nodes = [
            [
                'node_key' => 'Main',
                'message' => 'Welcome to Butal Ship Hauz. How can I assist you today?'
            ],
            [
                'node_key' => 'Venue Booking',
                'message' => 'Our venue spaces start at ₱5,000 per event. We offer 8 event spaces suitable for intimate gatherings, birthdays, meetings, weddings, and larger celebrations. Would you like to learn more about our venue options?'
            ],
            [
                'node_key' => 'Accommodations',
                'message' => 'We offer comfortable accommodations starting at ₱1,200 per night. Guests can enjoy a relaxing stay with access to the facilities of Butal Ship Hauz.'
            ],
            [
                'node_key' => 'Island Tours',
                'message' => 'We offer guided tours around Northern Bohol starting at ₱800 per person. These tours include visits to popular attractions, beaches, waterfalls, and other local destinations.'
            ],
            [
                'node_key' => 'Tour Routes',
                'message' => 'We currently offer 12 tour routes that include island hopping, waterfall visits, heritage sites, sightseeing, and snorkeling activities. All tours are guided and depart from Butal Ship Hauz.'
            ],
            [
                'node_key' => 'Event Planning',
                'message' => 'Our event planning packages start at ₱15,000. These packages can be customized for weddings, birthdays, reunions, corporate events, and other special occasions.'
            ],
            [
                'node_key' => 'Weddings at Ship Hauz',
                'message' => 'We offer wedding packages that may include venue rental, catering, event coordination, decorations, and accommodations. Contact us for a personalized quotation based on your requirements.'
            ],
            [
                'node_key' => 'Dining & Catering',
                'message' => 'Our restaurant serves a variety of local and Filipino dishes. Dining starts at approximately ₱350 per person. Catering services are also available for groups and special events.'
            ],
            [
                'node_key' => 'Photography Venue',
                'message' => 'Photography sessions start at ₱2,500. The venue is suitable for prenuptial shoots, debut pictorials, family photos, fashion photography, and commercial projects.'
            ],
            [
                'node_key' => 'Location & Hours',
                'message' => "Butal Ship Hauz\nTalibon, Bohol, Philippines\nOpen daily from 7:00 AM to 9:00 PM\nReservation inquiries are accepted from 8:00 AM to 6:00 PM\nContact Number: +63 (0) 912 345 6789"
            ],
            [
                'node_key' => 'Directions',
                'message' => 'Butal Ship Hauz is located in Talibon, Bohol. From Tagbilaran City, you may take a bus or van to Talibon. Travel time is approximately 2 to 3 hours depending on traffic and road conditions.'
            ],
            [
                'node_key' => 'Contact Us',
                'message' => "You may contact our team through the following:\nPhone: +63 (0) 912 345 6789\nEmail: reservations@butalshiphauz.com.ph\nWe aim to respond to inquiries within 24 hours."
            ],
            [
                'node_key' => 'Our Packages',
                'message' => "Day Tour Package - ₱999\nIncludes venue access, one meal, and a guided tour.\n\nOvernight Package - ₱2,499\nIncludes accommodation, meals, venue access, and a guided tour.\n\nWeekend Package - ₱4,299\nIncludes two nights accommodation, meals, tours, and event hall access.\n\nCustom packages are also available."
            ],
            [
                'node_key' => 'How to Book',
                'message' => "To make a reservation:\n1. Call +63 (0) 912 345 6789\n2. Email reservations@butalshiphauz.com.ph\n3. Use the Book a Reservation button on this website\n\nA 50 percent down payment is required to confirm a booking."
            ],
        ];

        $created = [];
        foreach ($nodes as $data) {
            $node = ChatBotNode::create(array_merge($data, ['status' => 'active']));
            $created[$node->node_key] = $node->id;
        }

        // Helper: resolve id by node_key
        $id = fn(string $key): int => $created[$key];

        // ── 2. Seed options (label + next_node_id per node) ───────────────
        $optionMap = [
            'Main' => [
                ['label' => 'Venue Booking', 'next' => 'Venue Booking'],
                ['label' => 'Accommodations', 'next' => 'Accommodations'],
                ['label' => 'Island Tours', 'next' => 'Island Tours'],
                ['label' => 'Event Planning', 'next' => 'Event Planning'],
                ['label' => 'Dining & Catering', 'next' => 'Dining & Catering'],
                ['label' => 'Photography Venue', 'next' => 'Photography Venue'],
                ['label' => 'Location & Hours', 'next' => 'Location & Hours'],
                ['label' => 'Contact Us', 'next' => 'Contact Us'],
            ],

            'Venue Booking' => [
                ['label' => 'Our Packages', 'next' => 'Our Packages'],
                ['label' => 'How to Book', 'next' => 'How to Book'],
                ['label' => 'Back to Menu', 'next' => 'Main'],
            ],

            'Accommodations' => [
                ['label' => 'Our Packages', 'next' => 'Our Packages'],
                ['label' => 'How to Book', 'next' => 'How to Book'],
                ['label' => 'Back to Menu', 'next' => 'Main'],
            ],

            'Island Tours' => [
                ['label' => 'See Tour Routes', 'next' => 'Tour Routes'],
                ['label' => 'Our Packages', 'next' => 'Our Packages'],
                ['label' => 'Back to Menu', 'next' => 'Main'],
            ],

            'Tour Routes' => [
                ['label' => 'How to Book', 'next' => 'How to Book'],
                ['label' => 'Back to Menu', 'next' => 'Main'],
            ],

            'Event Planning' => [
                ['label' => 'Weddings at Ship Hauz', 'next' => 'Weddings at Ship Hauz'],
                ['label' => 'Our Packages', 'next' => 'Our Packages'],
                ['label' => 'Back to Menu', 'next' => 'Main'],
            ],

            'Weddings at Ship Hauz' => [
                ['label' => 'Contact Us', 'next' => 'Contact Us'],
                ['label' => 'Back to Menu', 'next' => 'Main'],
            ],

            'Dining & Catering' => [
                ['label' => 'Event Planning', 'next' => 'Event Planning'],
                ['label' => 'Back to Menu', 'next' => 'Main'],
            ],

            'Photography Venue' => [
                ['label' => 'Weddings at Ship Hauz', 'next' => 'Weddings at Ship Hauz'],
                ['label' => 'Back to Menu', 'next' => 'Main'],
            ],

            'Location & Hours' => [
                ['label' => 'Directions', 'next' => 'Directions'],
                ['label' => 'Back to Menu', 'next' => 'Main'],
            ],

            'Directions' => [
                ['label' => 'Contact Us', 'next' => 'Contact Us'],
                ['label' => 'Back to Menu', 'next' => 'Main'],
            ],

            'Contact Us' => [
                ['label' => 'Back to Menu', 'next' => 'Main'],
            ],

            'Our Packages' => [
                ['label' => 'Contact Us', 'next' => 'Contact Us'],
                ['label' => 'Back to Menu', 'next' => 'Main'],
            ],

            'How to Book' => [
                ['label' => 'Contact Details', 'next' => 'Contact Us'],
                ['label' => 'Back to Menu', 'next' => 'Main'],
            ],
        ];

        foreach ($optionMap as $nodeKey => $options) {
            foreach ($options as $opt) {
                ChatBotNodeOption::create([
                    'chat_bot_node_id' => $id($nodeKey),
                    'option' => [
                        'label'       => $opt['label'],
                        'next_node_id' => $id($opt['next']),
                    ],
                    'status' => 'active',
                ]);
            }
        }
    }
}
