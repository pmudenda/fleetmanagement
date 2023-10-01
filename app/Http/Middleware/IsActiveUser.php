<?php

namespace App\Http\Middleware;

use App\Helpers\StatusHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsActiveUser
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->con_st_code != StatusHelper::activeUser()) {
            Auth::logout();
            return redirect('/login')
                ->withErrors(['message' => 'You Account Is blocked/suspended.']);
        }
        return $next($request);
    }
}
