@php
    $user = backpack_auth()->user();
    $userName = $user?->getAttribute('name') ?? 'Admin';
    $initial = mb_substr($userName, 0, 1, 'UTF-8');
@endphp

<li class="nav-item dropdown user-menu">
    <a class="nav-link user-menu-toggle" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
        <span class="user-avatar">
            <img src="{{ backpack_avatar_url($user) }}" alt="{{ $userName }}" onerror="this.style.display='none'">
            <span class="user-avatar-fallback">{{ $initial }}</span>
        </span>
        <span class="user-meta d-none d-md-flex flex-column">
            <span class="user-meta-name">{{ $userName }}</span>
            <span class="user-meta-role">{{ app()->getLocale() === 'ar' ? 'مشرف' : 'Administrator' }}</span>
        </span>
        <i class="la la-angle-down user-menu-caret d-none d-md-inline"></i>
    </a>

    <div class="dropdown-menu user-menu-dropdown {{ backpack_theme_config('html_direction') == 'rtl' ? 'dropdown-menu-start' : 'dropdown-menu-end' }}">
        <div class="user-menu-header">
            <div class="user-menu-header-name">{{ $userName }}</div>
            @if($user?->email)
                <div class="user-menu-header-email">{{ $user->email }}</div>
            @endif
        </div>
        <div class="dropdown-divider"></div>
        @if(config('backpack.base.setup_my_account_routes'))
            <a class="dropdown-item" href="{{ route('backpack.account.info') }}">
                <i class="la la-user"></i> {{ trans('backpack::base.my_account') }}
            </a>
            <div class="dropdown-divider"></div>
        @endif
        <a class="dropdown-item user-menu-logout" href="{{ backpack_url('logout') }}">
            <i class="la la-sign-out-alt"></i> {{ trans('backpack::base.logout') }}
        </a>
    </div>
</li>
