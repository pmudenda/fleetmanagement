@php use App\Services\Security\ParameterEncryption; @endphp
<div>
    <form method="POST" action="{{ route('user.reset.password') }}">
        @csrf
        <div class="">
            <div class="form-group">
                <label for="password"
                       class="col-form-label pl-2">
                    {{ __('One Time Password') }}
                </label>
                <div class="col-md-6 pl-0">
                    <input type="hidden" name="userId" value="{{ParameterEncryption::encrypt($user->id)}}">
                    <input id="password" type="otp"
                           class="form-control @error('otp') is-invalid @enderror"
                           name="otp" value="{{ old('otp') }}" required
                           autocomplete="otp" autofocus>
                    @error('otp')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-8">
                    <button type="submit" class="btn btn btn-sm btn-success">
                        <i class="fas fa-paper-plane"></i>
                        {{ __('Change Password') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
