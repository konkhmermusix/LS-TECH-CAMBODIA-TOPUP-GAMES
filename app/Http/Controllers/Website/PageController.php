<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PageController extends Controller
{
    public function faq(): View
    {
        $faqs = [
            [
                'q' => 'How long does diamond delivery take?',
                'a' => 'Our system connects directly to authorized Top-Up Provider APIs. Once your ABA KHQR payment is confirmed by the bank, diamonds are credited to your game account automatically within 5 to 30 seconds.'
            ],
            [
                'q' => 'Where do I find my Free Fire Player ID (UID)?',
                'a' => 'Open Free Fire on your mobile phone, tap your profile avatar in the top-left corner of the lobby screen. Your Player ID (8-10 digits) is displayed under your nickname. Tap the copy icon next to it.'
            ],
            [
                'q' => 'Where do I find my Mobile Legends User ID and Zone ID?',
                'a' => 'Open Mobile Legends: Bang Bang, tap your avatar in the top-left corner, and go to the "Basic Info" tab. Under your nickname, you will see your User ID (e.g. 12345678) and your 4-digit Zone ID inside brackets (e.g. 2024).'
            ],
            [
                'q' => 'How does ABA KHQR payment work?',
                'a' => 'After selecting your package and clicking Checkout, a unique ABA KHQR code is generated. Open your ABA Mobile app or any Bakong-enabled banking app in Cambodia, tap "Scan QR", scan the screen, and confirm. Your payment is verified instantly and top-up begins immediately.'
            ],
            [
                'q' => 'Do I need an account to top up?',
                'a' => 'No! We use Guest Checkout. You only need your Player UID and payment method. No password or registration is required.'
            ],
            [
                'q' => 'What if I entered the wrong Player ID?',
                'a' => 'Orders are processed instantly once payment is verified. Please double-check your UID before proceeding to checkout. If you need help, contact our 24/7 Telegram support with your Order Number immediately.'
            ],
            [
                'q' => 'What happens if a top-up fails?',
                'a' => 'If the game provider server is undergoing maintenance or experiencing a temporary outage, our system flags the order and our support team monitors it for automatic retry. You can check your live order status at any time using your Order Number.'
            ],
        ];

        return view('website.faq', compact('faqs'));
    }
}
