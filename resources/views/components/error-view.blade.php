<div>
    @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible">
            <p class="lead"> {{session()->get('message')}}</p>
        </div>
    @endif
    @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible">
            <p class="lead"> {{session()->get('error')}}</p>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="print-error-msg" style="display:none;">
        <ul style="color: red; font-weight: bold;"></ul>
    </div>
    <div class="alert alert-danger d-none" id="system_alert">
    </div>
</div>
