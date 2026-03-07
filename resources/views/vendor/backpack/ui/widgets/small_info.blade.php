<div class="col-6 col-lg-3">
    <div class="card">
        <div class="card-body p-3 d-flex align-items-center"><i
                class="bg-{{$widget['color']}} la la-{{$widget['icon']}} p-3 font-2xl mr-3"></i>
            <div>
                <div id="{{$widget['id'] ?? ''}}" class="text-value-sm text-{{$widget['color']}}">{{$widget['count']}}</div>
                <div class="text-muted text-uppercase font-weight-bold small">{{$widget['title']}}</div>
            </div>
        </div>
    </div>
</div>
