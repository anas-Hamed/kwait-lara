{{-- Notifications (visual placeholder) --}}
<li class="nav-item d-none d-sm-flex">
    <a class="nav-link topbar-icon-btn" href="{{ backpack_url('notifications') }}" title="{{ app()->getLocale() === 'ar' ? 'الإشعارات' : 'Notifications' }}">
        <i class="la la-bell"></i>
    </a>
</li>

{{-- Language Switcher --}}
<li class="nav-item">
    @if(app()->getLocale() === 'ar')
        <a class="nav-link lang-switch" href="{{ backpack_url('lang/en') }}" title="Switch to English">
            <span>EN</span>
        </a>
    @else
        <a class="nav-link lang-switch" href="{{ backpack_url('lang/ar') }}" title="التبديل إلى العربية">
            <span>ع</span>
        </a>
    @endif
</li>
