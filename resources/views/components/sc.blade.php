@props(['num' => null, 'icon' => null, 'title', 'sub' => null, 'tag' => null])
<div {{ $attributes->merge(['class' => 'sc']) }}>
    <div class="sc-head">
        @if ($num)<span class="sc-num">{{ $num }}</span>@elseif ($icon)<span class="sc-num ico"><i class="{{ $icon }}"></i></span>@endif
        <div>
            <h2>{{ $title }}@if ($tag)<span class="sc-tag">{{ $tag }}</span>@endif</h2>
            @if ($sub)<p>{{ $sub }}</p>@endif
        </div>
    </div>
    <div class="sc-body">{{ $slot }}</div>
</div>
