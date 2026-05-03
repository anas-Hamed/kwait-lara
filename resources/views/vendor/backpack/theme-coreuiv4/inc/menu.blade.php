@php
    $isRtl = backpack_theme_config('html_direction') == 'rtl';
@endphp

{{-- ============================================================== --}}
{{-- In LTR: greeting block sits at the start, profile block at end. --}}
{{-- In RTL: profile block sits at the start (right), greeting at end (left). --}}
{{-- ============================================================== --}}

@if ($isRtl)
    {{-- RTL: Profile section first (renders on the right, next to hamburger) --}}
    <ul class="header-nav">
        @if (backpack_auth()->guest())
            <li class="nav-item"><a class="nav-link" href="{{ route('backpack.auth.login') }}">{{ trans('backpack::base.login') }}</a></li>
            @if (config('backpack.base.registration_open'))
                <li class="nav-item"><a class="nav-link" href="{{ route('backpack.auth.register') }}">{{ trans('backpack::base.register') }}</a></li>
            @endif
        @else
            @include(backpack_view('inc.topbar_right_content'))
            @include(backpack_view('inc.menu_user_dropdown'))
        @endif
    </ul>

    {{-- RTL: Greeting block last with ms-auto (pushes it to the left edge) --}}
    <ul class="header-nav d-none d-lg-flex ms-auto">
        @if (backpack_auth()->check())
            @include(backpack_view('inc.topbar_left_content'))
        @endif
    </ul>
@else
    {{-- LTR: Greeting block first (renders on the left, next to hamburger) --}}
    <ul class="header-nav d-none d-lg-flex">
        @if (backpack_auth()->check())
            @include(backpack_view('inc.topbar_left_content'))
        @endif
    </ul>

    {{-- LTR: Profile block last with ms-auto (pushes it to the right edge) --}}
    <ul class="header-nav ms-auto">
        @if (backpack_auth()->guest())
            <li class="nav-item"><a class="nav-link" href="{{ route('backpack.auth.login') }}">{{ trans('backpack::base.login') }}</a></li>
            @if (config('backpack.base.registration_open'))
                <li class="nav-item"><a class="nav-link" href="{{ route('backpack.auth.register') }}">{{ trans('backpack::base.register') }}</a></li>
            @endif
        @else
            @include(backpack_view('inc.topbar_right_content'))
            @include(backpack_view('inc.menu_user_dropdown'))
        @endif
    </ul>
@endif
