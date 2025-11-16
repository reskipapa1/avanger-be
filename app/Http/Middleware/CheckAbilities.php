<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAbilities
{
    public function handle(Request $request, Closure $next, ...$abilities)
    {
        $token = $request->user()->currentAccessToken();

        if (!$token) {
            return response()->json(['message' => 'Token tidak ditemukan'], 401);
        }

        foreach ($abilities as $ability) {
            if (!$token->can($ability)) {
                return response()->json([
                    'message' => 'Token tidak memiliki kemampuan: ' . $ability
                ], 403);
            }
        }

        return $next($request);
    }
}
