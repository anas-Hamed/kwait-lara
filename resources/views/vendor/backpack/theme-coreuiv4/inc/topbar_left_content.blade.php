{{-- Topbar left: welcome + current date --}}
@php
    $user = backpack_auth()->user();
    $hour = (int) now()->format('H');
    if ($hour < 12) {
        $greetingKey = app()->getLocale() === 'ar' ? 'صباح الخير' : 'Good morning';
    } elseif ($hour < 18) {
        $greetingKey = app()->getLocale() === 'ar' ? 'طاب يومك' : 'Good afternoon';
    } else {
        $greetingKey = app()->getLocale() === 'ar' ? 'مساء الخير' : 'Good evening';
    }
    $dateLocale = app()->getLocale() === 'ar' ? 'ar' : 'en';
    $dateString = now()->locale($dateLocale)->isoFormat('dddd، D MMMM YYYY');
@endphp

<li class="nav-item topbar-welcome d-none d-md-flex flex-column justify-content-center">
    <span class="topbar-welcome-greeting">
        {{ $greetingKey }}@if($user), <strong>{{ $user->name }}</strong>@endif
    </span>
    <span class="topbar-welcome-date">{{ $dateString }}</span>
</li>
