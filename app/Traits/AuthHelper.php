<?php

namespace App\Traits;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

trait AuthHelper
{

    private function createBearer($user): array {
        $token_expires_at = Carbon::now()->addDays(7);
        $token            = $user->createToken('auth-token', $user->roles->pluck('name')->toArray(), $token_expires_at)->plainTextToken;
        return [
            'authorization' => $token,
            'exp'           => $token_expires_at->timestamp
        ];
    }

    private function removeUserAccessToken($bearer_token): void {
        if ($bearer_token) {
            [$id, $plain_text_token] = explode('|', $bearer_token, 2);
            $access_token = PersonalAccessToken::find($id);
            if ($access_token || hash_equals($access_token->token, hash('sha256', $plain_text_token))) {
                $access_token->delete();
            }
        }
    }

    private function removeWebGuard($request): void {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }
    }


}
