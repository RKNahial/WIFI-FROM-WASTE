@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<main class="py-8 bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-900 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @include('dashboard.partials.search')
        @include('dashboard.partials.metrics')
        @include('dashboard.partials.devices-table')
    </div>
</main>
@endsection