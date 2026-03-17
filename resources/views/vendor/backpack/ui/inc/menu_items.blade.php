@php
    $contact_us_count = \App\Models\ContactUs::query()->whereNull('read_at')->count();
    $new_companies = \App\Models\Company::query()->whereHasPaid(false)->count();
    $companies_updates = \App\Models\CompanyUpdate::query()->count();
    $companies_trust_request = \App\Models\CompanyTrustRequest::query()->count();
@endphp

<x-backpack::menu-item title="{{ trans('backpack::base.dashboard') }}" icon="la la-home" :link="backpack_url('dashboard')" />

<x-backpack::menu-item title="{{ __('menu.subscribers') }}" icon="la la-users" :link="backpack_url('user')" />

<x-backpack::menu-dropdown title="{{ __('menu.companies') }}" icon="la la-building">
    <li class="nav-item">
        <a class="nav-link" href="{{ backpack_url('company') }}">
            @if($new_companies)
                <span class="badge menu-badge position-absolute" style="{{ app()->getLocale() === 'ar' ? 'right: 10px' : 'left: 10px' }}">{{ $new_companies }}</span>
            @endif
            <i class="nav-icon"></i> <span>{{ __('menu.companies') }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ backpack_url('company-update') }}">
            @if($companies_updates)
                <span class="badge menu-badge position-absolute" style="{{ app()->getLocale() === 'ar' ? 'right: 10px' : 'left: 10px' }}">{{ $companies_updates }}</span>
            @endif
            <i class="nav-icon"></i> {{ __('menu.company_updates') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ backpack_url('company-trust-request') }}">
            @if($companies_trust_request)
                <span class="badge menu-badge position-absolute" style="{{ app()->getLocale() === 'ar' ? 'right: 10px' : 'left: 10px' }}">{{ $companies_trust_request }}</span>
            @endif
            <i class="nav-icon"></i> {{ __('menu.trust_requests') }}
        </a>
    </li>
    <x-backpack::menu-dropdown-item title="{{ __('menu.deleted_companies') }}" icon="" :link="backpack_url('deleted-company')" />
</x-backpack::menu-dropdown>

<x-backpack::menu-item title="{{ __('menu.categories') }}" icon="la la-list-ol" :link="backpack_url('category')" />

<x-backpack::menu-item title="{{ __('menu.ads') }}" icon="la la-newspaper-o" :link="backpack_url('ad')" />

<li class="nav-item contact-us">
    <a class="nav-link" href="{{ backpack_url('contactus') }}">
        @if($contact_us_count)
            <span class="badge menu-badge position-absolute" style="{{ app()->getLocale() === 'ar' ? 'right: 10px' : 'left: 10px' }}">{{ $contact_us_count }}</span>
        @endif
        <i class="nav-icon la la-envelope"></i> {{ __('menu.contact_messages') }}
    </a>
</li>

<x-backpack::menu-item title="{{ __('menu.blogs') }}" icon="la la-blog" :link="backpack_url('blog')" />

<x-backpack::menu-item title="{{ __('menu.plans') }}" icon="la la-tags" :link="backpack_url('plan')" />

<x-backpack::menu-item title="{{ __('menu.subscriptions') }}" icon="la la-clipboard-list" :link="backpack_url('subscription')" />

<x-backpack::menu-item title="{{ __('menu.qa') }}" icon="la la-question-circle" :link="backpack_url('qa-item')" />

<x-backpack::menu-item title="{{ __('menu.notifications') }}" icon="la la-bell" :link="backpack_url('notification/create')" />

<x-backpack::menu-item title="{{ __('menu.settings') }}" icon="la la-cog" :link="backpack_url('setting')" />
