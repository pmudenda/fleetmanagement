@extends('layouts.app')
@push('page_level_style')
    <style>
        /* body{
             background:#eee;
         }*/
        .card {
            box-shadow: 0 20px 27px 0 rgb(0 0 0 / 5%);
        }

        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 0 solid rgba(0, 0, 0, .125);
            border-radius: 1rem;
        }

        .img-thumbnail {
            padding: .25rem;
            background-color: #F59D33;
            border: 1px solid #dee2e6;
            border-radius: .25rem;
            max-width: 100%;
            height: auto;
        }

        .avatar-lg {
            height: 150px;
            width: 150px;
        }

        .center_content {
            align-items: center;
            display: flex;
            height: 100vh;
            justify-content: center;
            left: 0;
            overflow: auto;
            position: fixed;
            top: 0;
            touch-action: none;
            width: 100%;
            z-index: 100;
        }

        .announce {
            /*background-color: #fff;*/
            border-radius: 8px;
            /* box-shadow: 0 0 20px 2px rgba(0,0,0,.3);*/
            color: #333;
            cursor: default;
            display: flex;
            flex-flow: column;
            font-size: 14px;
            margin: auto 0;
            /*max-width: 840px;*/
            overflow: hidden;
            position: relative;
            width: 100%;
        }

        .announce .content {
            display: flex;
            flex-flow: row;
            min-height: 220px;
        }

        .input-control {
            height: 45px !important;
        }

        .form-control:focus {
            border-color: #00b44e !important;
            border-width: 2px;
        }

        .form-control.valid-input {
            border-color: #00b44e !important;
            border-width: 2px;
            font-weight: bold;
            font-size: 22px !important;
            color: #00b44e;
        }

        .form-control.invalid-input {
            border-color: #c70505 !important;
            border-width: 2px;
            color: #c70505;
        }

        .spinner {
            animation: rotation;
        }

        @keyframes rotation {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(100deg);
            }
        }
    </style>
@endpush
@section('content')
    <div class="container d-none">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="row justify-content-center ">
                    <img src="{{asset('dashboard/dist/img/ZESCO_removebg.png')}}" width="50%">
                </div>

                <div class="card">
                    <div class="card-header">2FA Verification</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('2fa.post') }}">
                            @csrf

                            <p class="text-center">
                                We sent code to your phone :
                                {{ substr(auth()->user()->phone, 0, 5) . '******' . substr(auth()->user()->phone,  -2) }}
                            </p>

                            @if ($message = Session::get('success'))
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-success alert-block">
                                            <button type="button" class="close" data-dismiss="alert">×</button>
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($message = Session::get('error'))
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger alert-block">
                                            <button type="button" class="close" data-dismiss="alert">×</button>
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="form-group row">
                                <label for="code" class="col-md-4 col-form-label text-md-right">Code</label>

                                <div class="col-md-6">
                                    <input id="code" type="number"
                                           class="form-control @error('code') is-invalid @enderror" name="code"
                                           value="{{ old('code') }}" required autocomplete="code" autofocus>

                                    @error('code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <a class="btn btn-link" href="{{ route('2fa.resend') }}">Resend Code?</a>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Submit
                                    </button>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="center_content">
        {{--<div class="row">--}}
        <div class="announce" style="overflow: unset;">
            <div class="col-lg-5 col-md-7 mx-auto my-auto content" style="padding-top: 20px;
            display: flex;
            position: relative;
            flex-direction: column;
            align-items: stretch;
            width: 520px;
            min-height: 420px;
            flex-grow: 0;
            flex-shrink: 0;">
                <div class="card">
                    <div class="card-body px-lg-5 py-lg-3 text-center">
                        <img src="{{asset('dashboard/dist/img/logo_.png')}}"
                             class="avatar-lg rounded-circle img-thumbnail mb-4"
                             alt="profile-image">

                        <h2 class="text-info">2FA Security</h2>
                        {{--from your athenticatior app.--}}
                        <p class="mb-4">
                            Enter 5-digits code sent code to your phone :
                            {{ substr(auth()->user()->phone, 0, 3) . '******' . substr(auth()->user()->phone,  -2) }}
                        </p>
                        <form method="POST" id="otpForm" name="otpForm" action="{{ route('2fa.post') }}">
                            @csrf
                            <div class="row mb-4">
                                <div class="col-lg-1 col-md-1 col-1 pe-0 pe-md-1"></div>
                                <div class="col-lg-2 col-md-2 col-2 ps-0 ps-md-2">
                                    <input type="text" name="otp1"
                                           maxlength="1"
                                           class="form-control otp input-control text-lg text-center @error('otp1') invalid-input @enderror"
                                           placeholder="_"
                                           aria-label="2fa">
                                </div>
                                <div class="col-lg-2 col-md-2 col-2 ps-0 ps-md-2">
                                    <input type="text" name="otp2"
                                           maxlength="1"
                                           class="form-control otp input-control text-lg text-center @error('otp2') invalid-input @enderror"
                                           placeholder="_"
                                           aria-label="2fa">
                                </div>
                                <div class="col-lg-2 col-md-2 col-2 ps-0 ps-md-2">
                                    <input type="text" name="otp3"
                                           maxlength="1"
                                           class="form-control otp input-control text-lg text-center @error('code') invalid-input @enderror"
                                           placeholder="_"
                                           aria-label="2fa">
                                </div>
                                <div class="col-lg-2 col-md-2 col-2 pe-0 pe-md-2">
                                    <input type="text" name="otp4"
                                           maxlength="1"
                                           class="form-control otp input-control text-lg text-center @error('otp4') invalid-input @enderror"
                                           placeholder="_"
                                           aria-label="2fa">
                                </div>
                                <div class="col-lg-2 col-md-2 col-2 pe-0 pe-md-2">
                                    <input type="text" name="otp5"
                                           maxlength="1"
                                           class="form-control otp input-control text-lg text-center @error('otp5') invalid-input @enderror"
                                           placeholder="_"
                                           aria-label="2fa">
                                </div>
                                <div class="col-lg-1 col-md-1 col-1 pe-0 pe-md-1"></div>
                            </div>
                            <div>
                                @if ($message = Session::get('success'))
                                    <div class="alert alert-success alert-block">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @endif

                                @if ($message = Session::get('error'))
                                    <div class="alert alert-danger alert-block">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @endif

                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-success btn-lg mt-4">
                                    Continue
                                </button>
                            </div>
                            <div class="text-center">
                                <a class="btn btn-link" href="{{ route('2fa.resend') }}">
                                    Resend Code?
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{--  </div>--}}
    </div>

@endsection
@push('page_level_script')
    <script>
        window.onload = function () {
            document.querySelectorAll('.otp').forEach(function (element, key) {
                element.addEventListener('keyup', function (event) {
                    let $target = event.target;
                    if ($target.value) {
                        $target.classList.remove('invalid-input');
                        $target.classList.add('valid-input');
                        let inputMaxLength = parseInt($target.attributes["maxlength"].value, 10);
                        let inputLength = $target.value.length;
                        if (inputLength === inputMaxLength) {
                            let next = $target;
                            while (next = next.parentNode.nextElementSibling.children[0]) {
                                if (next == null)
                                    break;
                                if (next.tagName.toLowerCase() === "input") {
                                    next.focus();
                                    break;
                                }
                            }
                        }
                        // Move to previous field if empty (user pressed backspace)
                        else if (inputLength === 0) {
                            let previous = target;
                            while (previous = previous.previousElementSibling) {
                                if (previous == null)
                                    break;
                                if (previous.tagName.toLowerCase() === "input") {
                                    previous.focus();
                                    break;
                                }
                            }
                        }

                    } else {
                        event.currentTarget.classList.remove('valid-input');
                        event.currentTarget.classList.add('invalid-input');
                    }
                });
            });
            document.querySelector('#otpForm').addEventListener('submit', function (event) {
                event.preventDefault();
                event.submitter.innerHTML = `<div class="row">
                                        <div class="spinner ml-3 mr-3">
                                            <i class="fa fa-spinner"></i>
                                        </div>
                                        <span class="mr-3">Processing..</span>
                                    </div>`;
                event.currentTarget.removeEventListener('submit', function (event) {
                    console.log('Event Detached');
                });
                event.currentTarget.submit();
            });
        };
    </script>
@endpush
