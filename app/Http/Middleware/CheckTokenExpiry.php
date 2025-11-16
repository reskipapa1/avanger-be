<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckTokenExpiry
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->user()->currentAccessToken();

        if (!$token) {
            return response()->json([
                'message' => 'Token tidak ditemukan'
            ], 401);
        }

        // ========== TOKEN EXPIRED AFTER 1 HOUR ==========
        $expiredHours = 1;

        if ($token->created_at->diffInHours(now()) >= $expiredHours) {
            // Hapus token yang sudah expired
            $token->delete();

            return response()->json([
                'message' => 'Token expired, silakan login ulang'
            ], 401);
        }
        // =================================================

        return $next($request);
    }
}
