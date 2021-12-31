<?php

namespace App\Http\Middleware;

use Closure;

class RequiresGameOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $game = request()->route('game');

        // Verify that user has ownership over the game being updated
        if ($game->user->id != $request->user()->id) {
            abort(403);
        }

        return $next($request);
    }
}
