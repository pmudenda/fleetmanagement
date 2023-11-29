@extends('layouts.auth')

@section('content')

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="text-center">
            <div class="mb-5">
                <h1 class="display-4 mt-0 font-weight-semi-bold">
                    FLEET MASTER
                </h1>
                <small>A TRANSPORT MANAGEMENT SYSTEM</small>
            </div>
        </div>
        <p class="login-box-msg">
            You forgot your password? Don't worry, it happens to the best of us.
        </p>
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <div class="input-group mb-3">
            <input id="email" type="email" placeholder="Email "
                   class="form-control @error('email') is-invalid @enderror"
                   name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            @error('email')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">
                    Submit
                </button>
            </div>
            <!-- /.col -->
        </div>
        <p class="mt-3 mb-1">
            <a href="{{route('login')}}">Back to Login</a>
        </p>
    </form>
@endsection
