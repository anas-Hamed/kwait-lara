@includeWhen(!empty($widget['wrapper']), backpack_view('widgets.inc.wrapper_start'))
<div class="card quick-panel">
    <div class="panel-header">
        <i class="la la-bolt"></i> {{ __('dashboard.quick_actions') }}
    </div>
    <div class="quick-actions-list">
        @foreach($widget['actions'] ?? [] as $action)
            <a href="{{ $action['url'] }}" class="quick-action-btn">
                <div class="action-icon bg-{{ $action['color'] ?? 'primary' }}">
                    <i class="la {{ $action['icon'] }}"></i>
                </div>
                <span>{{ $action['label'] }}</span>
            </a>
        @endforeach
    </div>
</div>
@includeWhen(!empty($widget['wrapper']), backpack_view('widgets.inc.wrapper_end'))
