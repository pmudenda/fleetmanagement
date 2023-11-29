@php use App\Services\Security\ParameterEncryption; @endphp
<div>
    <form method="POST" action="{{ route('user.reset.password') }}">
        <x-error-view />
        @csrf
        <div class="">
            <div class="form-group">
                <label for="password"
                       class="col-form-label pl-2">
                    {{ __('Password') }}
                </label>
                <div class="col-md-6 pl-0">
                    <input type="hidden" name="userId" value="{{ParameterEncryption::encrypt($user->id)}}">
                    <input id="password" type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           name="otp" value="{{ old('password') }}" required
                           autocomplete="password" autofocus>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors }}</strong>
                    </span>
                    @enderror

                    @error('password')
                    <div class="alert alert-danger">{{ $message }}</div>
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
