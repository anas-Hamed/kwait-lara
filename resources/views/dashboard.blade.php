@extends(backpack_view('blank'))

@section('before_content_widgets')
<div class="dashboard-header">
    <h2>{{ __('dashboard.title') }}</h2>
    <p class="mb-0">{{ __('dashboard.welcome', ['name' => backpack_user()->name]) }}</p>
</div>
@include(backpack_view('inc.widgets'), [ 'widgets' => app('widgets')->where('section', 'before_content')->toArray() ])
@endsection

@section('content')
@endsection
