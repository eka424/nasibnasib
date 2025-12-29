@extends('layouts.app')

@section('title', 'Kelola Kegiatan')

@section('content')
    <div class="-m-6">
        <div class="flex min-h-screen w-full flex-col bg-muted/40">
            @include('admin.partials.pengurus-kegiatan-content')
        </div>
    </div>
@endsection
