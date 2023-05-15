<?php

namespace App\Http\Controllers;

use App\Helpers\ConfigHelper;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function logout(): Application|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $user = auth()->user();
        $user->has_active_session = ConfigHelper::currentLoginFalse();
        $user->save();
        Auth::logout();
        return redirect('/login')->with(['msg_body' => 'Signing out!']);
    }

    public function dashboard(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('dashboard.home');
    }
}
