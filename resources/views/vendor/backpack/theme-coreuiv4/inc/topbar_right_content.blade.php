{{-- Language Switcher --}}
<li class="nav-item">
    @if(app()->getLocale() === 'ar')
        <a class="nav-link lang-switch" href="{{ backpack_url('lang/en') }}" title="Switch to English">
            <span style="font-size: 0.85rem; font-weight: 600;">EN</span>
        </a>
    @else
        <a class="nav-link lang-switch" href="{{ backpack_url('lang/ar') }}" title="التبديل إلى العربية">
            <span style="font-size: 0.85rem; font-weight: 600;">ع</span>
        </a>
    @endif
</li>
