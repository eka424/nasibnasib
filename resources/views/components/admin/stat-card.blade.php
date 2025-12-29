@props([
    'label',
    'value' => 0,
    'description' => null,
    'icon' => null,
    'format' => 'number',
    'prefix' => null,
    'suffix' => null,
])

@php
    $icons = [
        'megaphone' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 8V4l-5 4H5v6h6l5 4v-4l4-2V10l-4-2Z" /></svg>',
        'hourglass' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 4h12M6 20h12M8 4v4l3 3-3 3v6M16 4v4l-3 3 3 3v6" /></svg>',
        'check-circle' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9" /><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.5 11 14.5 15 9.5" /></svg>',
        'clipboard' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="7" y="4" width="10" height="16" rx="2" /><path stroke-linecap="round" stroke-linejoin="round" d="M9 8h6M9 12h4M9 16h3" /></svg>',
        'user-plus' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="9" cy="8" r="3" /><path stroke-linecap="round" stroke-linejoin="round" d="M4 20a5 5 0 0 1 10 0" /><path stroke-linecap="round" stroke-linejoin="round" d="M17 9v6M14 12h6" /></svg>',
        'user-check' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="9" cy="8" r="3" /><path stroke-linecap="round" stroke-linejoin="round" d="M4 20a5 5 0 0 1 10 0" /><path stroke-linecap="round" stroke-linejoin="round" d="M16 17.5 18 19.5 22 15.5" /></svg>',
        'users' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 14a4 4 0 1 1 4 4H16m-8 0H4a4 4 0 1 1 4-4m8-6a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z" /></svg>',
        'dollar' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18M7 8c0-1.66 2.24-3 5-3s5 1.34 5 3-2.24 3-5 3-5 1.34-5 3 2.24 3 5 3 5-1.34 5-3" /></svg>',
        'library' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 3H6a2 2 0 0 0-2 2v14M16 3h2a2 2 0 0 1 2 2v14M12 3v18M3 7h18" /></svg>',
        'image' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" /><circle cx="8.5" cy="8.5" r="1.5" /><path stroke-linecap="round" stroke-linejoin="round" d="m21 15-4.5-4.5L9 18" /></svg>',
        'video' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="15" height="14" rx="2" /><path stroke-linecap="round" stroke-linejoin="round" d="M18 8l3 2v4l-3 2z" /></svg>',
        'eye' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12Z" /><circle cx="12" cy="12" r="3" /></svg>',
        'question' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10a4 4 0 1 1 4 4v2" /><circle cx="12" cy="19" r="1" /><circle cx="12" cy="12" r="9" /></svg>',
    ];

    $valueIsNumeric = is_numeric($value);
    $displayValue = $valueIsNumeric ? number_format((float) $value) : (string) $value;

    if ($valueIsNumeric && $format === 'currency') {
        $currencyPrefix = $prefix ?? 'Rp';
        $displayValue = trim($currencyPrefix . ' ' . number_format((float) $value, 0, ',', '.'));
        $prefix = null;
    } elseif ($valueIsNumeric) {
        $displayValue = number_format((float) $value);
    }

    if ($prefix) {
        $displayValue = trim($prefix . ' ' . $displayValue);
    }

    if ($suffix) {
        $displayValue = trim($displayValue . ' ' . $suffix);
    }
@endphp

<div class="rounded-xl border border-slate-200 bg-white text-slate-900 shadow-sm">
    <div class="flex flex-row items-center justify-between space-y-0 px-6 pb-2 pt-6">
        <p class="text-sm font-medium text-slate-700">{{ $label }}</p>
        @if ($icon && isset($icons[$icon]))
            {!! $icons[$icon] !!}
        @endif
    </div>
    <div class="px-6 pb-6">
        <div class="text-2xl font-bold">{{ $displayValue }}</div>
        @if ($description)
            <p class="text-xs text-slate-500">{{ $description }}</p>
        @endif
    </div>
</div>
