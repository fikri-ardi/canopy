<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TrackAuthenticatedActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && (! $user->last_seen_at || $user->last_seen_at->lt(now()->subMinute()))) {
            $user->forceFill([
                'last_seen_at' => now(),
                'online_until' => now()->addMinutes(5),
            ])->saveQuietly();
        }

        if ($user) {
            DB::table('user_activity_days')->insertOrIgnore([
                'user_id' => $user->id,
                'active_on' => now()->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $next($request);
    }
}
