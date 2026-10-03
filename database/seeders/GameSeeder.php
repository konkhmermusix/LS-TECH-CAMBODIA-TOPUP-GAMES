<?php

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    public function run(): void
    {
        Game::updateOrCreate(
            ['slug' => 'free-fire'],
            [
                'name' => 'Free Fire',
                'publisher' => 'Garena',
                'logo' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=200&auto=format&fit=crop&q=80',
                'banner' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=1200&auto=format&fit=crop&q=80',
                'description' => 'Fast, official Free Fire diamond instant top-up with ABA KHQR payment in Cambodia. Instant delivery to your player UID.',
                'instruction' => 'Open Free Fire, click on your profile icon in the top left corner of the main lobby screen. Your Player ID (UID) is displayed under your nickname.',
                'input_fields' => [
                    [
                        'key' => 'player_id',
                        'label' => 'Player UID',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Enter 8-10 digit Player UID',
                        'help_text' => 'Example: 3245770826 (Found in Free Fire Profile)',
                        'pattern' => '^[0-9]{8,12}$',
                    ]
                ],
                'validation_endpoint' => null,
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        Game::updateOrCreate(
            ['slug' => 'mobile-legends'],
            [
                'name' => 'Mobile Legends: Bang Bang',
                'publisher' => 'Moonton',
                'logo' => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?w=200&auto=format&fit=crop&q=80',
                'banner' => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?w=1200&auto=format&fit=crop&q=80',
                'description' => 'Official Mobile Legends Diamonds & Weekly Diamond Pass instant top-up. Automatic processing with verified ABA KHQR.',
                'instruction' => 'Tap on your avatar in the top-left corner of the game screen. Go to the "Basic Info" tab. You will see your User ID (e.g., 12345678) and Zone ID in brackets (e.g., 2024).',
                'input_fields' => [
                    [
                        'key' => 'player_id',
                        'label' => 'User ID',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'e.g. 12345678',
                        'help_text' => 'Enter your 8-9 digit User ID',
                        'pattern' => '^[0-9]{6,12}$',
                    ],
                    [
                        'key' => 'zone_id',
                        'label' => 'Zone ID / Server',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'e.g. 2024',
                        'help_text' => 'Enter 4-5 digits in brackets next to User ID',
                        'pattern' => '^[0-9]{3,6}$',
                    ]
                ],
                'validation_endpoint' => null,
                'is_active' => true,
                'sort_order' => 2,
            ]
        );
    }
}
