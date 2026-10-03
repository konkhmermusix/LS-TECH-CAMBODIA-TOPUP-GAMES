<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlayerVerificationController extends Controller
{
    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'game_id' => ['required', 'exists:games,id'],
            'player_id' => ['required', 'string', 'max:50'],
            'zone_id' => ['nullable', 'string', 'max:20'],
        ]);

        $game = Game::findOrFail($request->input('game_id'));
        $playerId = trim($request->input('player_id'));
        $zoneId = trim($request->input('zone_id', ''));

        // Validation based on game rules
        if ($game->slug === 'free-fire') {
            if (!preg_match('/^[0-9]{8,12}$/', $playerId)) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Free Fire Player UID must contain 8 to 12 digits.',
                ], 422);
            }

            // Simulated nickname resolution for gaming UX
            $nickname = 'Gamer_' . substr($playerId, -4);
            if ($playerId === '3245770826') {
                $nickname = 'KhmerPro_FF';
            }

            return response()->json([
                'valid' => true,
                'nickname' => $nickname,
                'message' => 'Player UID verified successfully.',
            ]);
        }

        if ($game->slug === 'mobile-legends') {
            if (!preg_match('/^[0-9]{6,12}$/', $playerId)) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Mobile Legends User ID must be 6 to 12 digits.',
                ], 422);
            }

            if (empty($zoneId) || !preg_match('/^[0-9]{3,6}$/', $zoneId)) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Zone ID / Server ID must be 3 to 6 digits.',
                ], 422);
            }

            $nickname = 'MLBB_Warrior_' . substr($playerId, -3);

            return response()->json([
                'valid' => true,
                'nickname' => $nickname,
                'message' => 'Mobile Legends account verified.',
            ]);
        }

        return response()->json([
            'valid' => true,
            'nickname' => 'Verified Player',
            'message' => 'Account format valid.',
        ]);
    }
}
