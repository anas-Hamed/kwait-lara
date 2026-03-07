@includeWhen(!empty($widget['wrapper']), backpack_view('widgets.inc.wrapper_start'))
<div class="card activity-panel">
    <div class="panel-header">
        <i class="la la-stream"></i> {{ __('dashboard.recent_activity') }}
    </div>
    <div class="activity-list">
        @forelse($widget['activities'] ?? [] as $activity)
            <div class="activity-item">
                <div class="activity-dot dot-{{ $activity['color'] ?? 'primary' }}"></div>
                <div>
                    <div class="activity-text">{{ $activity['description'] }}</div>
                    <div class="activity-time">{{ \Carbon\Carbon::parse($activity['time'])->diffForHumans() }}</div>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-4">
                <i class="la la-inbox" style="font-size: 2rem;"></i>
                <p class="mt-2 mb-0">{{ __('dashboard.no_activity') }}</p>
            </div>
        @endforelse
    </div>
</div>
@includeWhen(!empty($widget['wrapper']), backpack_view('widgets.inc.wrapper_end'))
