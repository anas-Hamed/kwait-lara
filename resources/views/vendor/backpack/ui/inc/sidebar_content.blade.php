@php
    $contact_us_count = \App\Models\ContactUs::query()->whereNull('read_at')->count();
    $new_companies = \App\Models\Company::query()->whereHasPaid(false)->count();
    $companies_updates = \App\Models\CompanyUpdate::query()->count();
    $companies_trust_request = \App\Models\CompanyTrustRequest::query()->count();
@endphp

<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('dashboard') }}">
        <i class="nav-icon la la-home"></i> {{ trans('backpack::base.dashboard') }}
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('user') }}">
        <i class="nav-icon la la-users"></i> المشتركين
    </a>
</li>

<li class="nav-group">
    <a class="nav-link nav-group-toggle" href="#">
        <i class="nav-icon la la-building"></i> الشركات
    </a>
    <ul class="nav-group-items">
        <li class="nav-item">
            <a class="nav-link" href="{{ backpack_url('company') }}">
                @if($new_companies)
                    <span class="badge badge-danger position-absolute" style="right: 10px">{{$new_companies}}</span>
                @endif
                <i class="nav-icon"></i> <span>الشركات</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ backpack_url('company-update') }}">
                @if($companies_updates)
                    <span class="badge badge-danger position-absolute" style="right: 10px">{{$companies_updates}}</span>
                @endif
                <i class="nav-icon"></i> تحديثات الشركات
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ backpack_url('company-trust-request')}}">
                @if($companies_trust_request)
                    <span class="badge badge-danger position-absolute" style="right: 10px">{{$companies_trust_request}}</span>
                @endif
                <i class="nav-icon"></i> طلبات التوثيق
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ backpack_url('deleted-company') }}">
                <i class="nav-icon"></i> الشركات المحذوفة
            </a>
        </li>
    </ul>
</li>

<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('category') }}">
        <i class="nav-icon la la-list-ol"></i> التصنيفات
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('ad') }}">
        <i class="nav-icon la la-newspaper-o"></i> الإعلانات
    </a>
</li>

<li class="nav-item contact-us">
    <a class="nav-link" href="{{ backpack_url('contactus') }}">
        @if($contact_us_count)
            <span class="badge badge-danger position-absolute" style="right: 10px">{{$contact_us_count}}</span>
        @endif
        <i class="nav-icon la la-envelope"></i> رسائل المستخدمين
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('blog') }}">
        <i class="nav-icon la la-blog"></i> المدونات
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('notification/create') }}">
        <i class="nav-icon la la-bell"></i> الإشعارات
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('setting') }}">
        <i class="nav-icon la la-cog"></i> الإعدادات
    </a>
</li>
