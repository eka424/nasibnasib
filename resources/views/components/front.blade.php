@props(['immersive' => false])

@include('layouts.front', [
    'slot' => $slot,
    'immersive' => $immersive
])
