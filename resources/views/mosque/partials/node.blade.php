@php
  $children = $node->children;
@endphp

<div class="flex flex-col items-center">
    {{-- BOX NODE --}}
    <div class="rounded-2xl bg-white/10 border border-white/10 px-4 py-3 text-center min-w-[220px]">
        <div class="font-bold">{{ $node->jabatan }}</div>
        @if($node->nama)
            <div class="text-sm text-[#DAF0DC]/85 mt-1">{{ $node->nama }}</div>
        @else
            <div class="text-sm text-[#DAF0DC]/50 mt-1">—</div>
        @endif
    </div>

    {{-- CONNECT LINE DOWN --}}
    @if($children->count() > 0)
        <div class="w-px h-6 bg-white/20"></div>

        {{-- CHILDREN ROW --}}
        <div class="flex flex-wrap justify-center gap-6">
            @foreach($children as $child)
                <div class="flex flex-col items-center">
                    {{-- line up --}}
                    <div class="w-px h-6 bg-white/20"></div>
                    @include('mosque.partials.node', ['node' => $child])
                </div>
            @endforeach
        </div>
    @endif
</div>
