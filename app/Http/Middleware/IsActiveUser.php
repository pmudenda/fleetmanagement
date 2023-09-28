<?php

namespace App\Http\Middleware;

use App\Helpers\StatusHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        Log::debug("User Active Middleware");
        Log::debug("Status ". StatusHelper::activeUser());
        if(auth()->user()->con_st_code != StatusHelper::activeUser()){
            Auth::logout();
            return redirect('/login')->withErrors(['message'=>'You Account Is blocked/suspended.']);
        }
        return $next($request);
    }
}
