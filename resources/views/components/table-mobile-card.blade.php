@props(['data' => [], 'actions' => null])

{{-- Mobile card layout for table data --}}
<div class="table-mobile-card">
    @foreach($data as $label => $value)
        <div class="table-mobile-row">
            <span class="table-mobile-label">{{ $label }}</span>
            <span class="table-mobile-value">{!! $value !!}</span>
        </div>
    @endforeach
    
    @if($actions)
        <div class="table-mobile-row border-t pt-3 mt-3">
            <span class="table-mobile-label">Actions</span>
            <div class="table-mobile-value">
                {!! $actions !!}
            </div>
        </div>
    @endif
</div>
