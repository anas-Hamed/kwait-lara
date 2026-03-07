@includeWhen(!empty($widget['wrapper']), backpack_view('widgets.inc.wrapper_start'))
<div class="card pending-panel">
    <div class="panel-header">
        <i class="la la-tasks"></i> {{ __('dashboard.pending_tasks') }}
    </div>
    @foreach($widget['items'] ?? [] as $item)
        <a href="{{ $item['url'] }}" class="pending-item">
            <div class="pending-icon bg-{{ $item['color'] ?? 'primary' }}">
                <i class="la {{ $item['icon'] }}"></i>
            </div>
            <div class="pending-label">{{ $item['label'] }}</div>
            <div class="pending-count">{{ $item['count'] }}</div>
        </a>
    @endforeach
</div>
@includeWhen(!empty($widget['wrapper']), backpack_view('widgets.inc.wrapper_end'))
