@props(['disabled' => false])

<button {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'type' => 'submit',
    'class' =>
        'inline-flex items-center px-4 py-2 bg-[#44867e] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:brightness-110 focus:bg-[#44867e] active:bg-[#44867e] focus:outline-none focus:ring-2 focus:ring-[#ffbd59] focus:ring-offset-2 transition ease-in-out duration-150'
]) !!}>
    {{ $slot }}
</button>
