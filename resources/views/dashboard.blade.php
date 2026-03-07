@extends(backpack_view('blank'))

@section('content')
<div class="dashboard-header">
    <h2>{{ __('dashboard.title') }}</h2>
    <p class="mb-0">{{ __('dashboard.welcome', ['name' => backpack_user()->name]) }}</p>
</div>
@endsection
