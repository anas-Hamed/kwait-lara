<div class="{{ $widget['wrapper']['class'] ?? 'col-6 col-lg-2' }} mb-3">
    <div class="card stat-card {{ $widget['gradient'] ?? 'gradient-1' }}">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ $widget['count'] ?? 0 }}</div>
                    <div class="stat-label">{{ $widget['title'] ?? '' }}</div>
                    @if(isset($widget['trend']))
                        <div class="stat-trend">
                            <i class="la {{ ($widget['trend_up'] ?? true) ? 'la-arrow-up' : 'la-arrow-down' }}"></i>
                            {{ $widget['trend'] }}
                        </div>
                    @endif
                </div>
                <div class="stat-icon">
                    <i class="la la-{{ $widget['icon'] ?? 'chart-bar' }}"></i>
                </div>
            </div>
        </div>
    </div>
</div>
