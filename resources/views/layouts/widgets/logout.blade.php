<a href="#" class="menu-link px-5" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
    {{--<i class="fas fa-sign-out-alt mr-2"></i>--}}
    Sign Out
</a>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
