<div class="{{ $widget['wrapper']['class'] ?? 'col-6 col-lg-3' }} mb-3">
    <div class="card h-100">
        <div class="card-body p-3 d-flex align-items-center gap-3">
            <div class="rounded-3 d-flex align-items-center justify-content-center"
                 style="width: 48px; height: 48px; min-width: 48px; background: var(--cui-{{ $widget['color'] ?? 'primary' }}, #7c69ef); opacity: 0.9;">
                <i class="la la-{{ $widget['icon'] ?? 'question' }} text-white" style="font-size: 1.4rem;"></i>
            </div>
            <div>
                <div class="fs-4 fw-semibold" id="{{ $widget['id'] ?? '' }}">{{ $widget['count'] }}</div>
                <div class="text-muted small text-uppercase fw-semibold">{{ $widget['title'] }}</div>
            </div>
        </div>
    </div>
</div>
