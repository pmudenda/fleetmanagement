<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class ChangePasswordOnNextLogin
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::debug("Running Change Password on Next Login Middleware");

        if (auth()->user()->change_password_next_login == 'Y') {
            return redirect(URL::signedRoute('password.change', ['key' => auth()->user()->id]));
        }

        return $next($request);
    }
}
