@extends('layouts.auth')
@section('content')
    <form method="POST" action="{{route('login')}}" id="loginForm" novalidate=""
          accept-charset="utf-8">
        @csrf

        <div class="text-center">
            <div class="mb-5">
                <h1 class="display-4 mt-0 font-weight-semi-bold">
                    FLEET MASTER
                </h1>
                <small>A VEHICLE TRACKING & TRANSPORT MANAGEMENT SYSTEM</small>
            </div>
        </div>
        <div class="form-group">
            <label class="input-label font-weight-bold" for="username">Staff Number</label>
            <input type="text" name="email"
                   value="{{ old('email') }}"
                   autocomplete="off" id="username"
                   placeholder=""
                   required=""
                   autofocus=""
                   class="form-control form-control-sm @error('email') is-invalid @enderror"/>
            @error('email')
            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror
        </div>
        <div class="form-group">
            <label class="input-label font-weight-bold" for="inputPassword">Password</label>
            <div class="position-relative">
                <input type="password"
                       placeholder="Password"
                       class="form-control form-control-sm password @error('password') is-invalid @enderror"
                       name="password"
                       id="password"
                       required=""/>
                <i onclick="passShow()" class="fa fa-eye-slash"></i>
            </div>
            @error('password')
            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror
        </div>


        <div class="form-group row mb-0">
            <div class="form-group col-md-4">
                <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-paper-plane"></i> Login
                </button>
            </div>
            <div class="col-md-8 pl-0">
                @if (Route::has('password.request'))
                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password ?') }}
                    </a>
                @endif
            </div>
        </div>
    </form>
@endsection
