@extends('layouts.center_dashboard')

@section('title', 'Welcome')

@section('content')
    <div class="flex items-center justify-center min-h-screen">
        <div class="text-center">
            <i class="fas fa-building text-6xl text-gray-300 mb-4"></i>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">No Center Found</h1>
            <p class="text-gray-600 mb-6">You need to be associated with a center to use the dashboard</p>
            <p class="text-sm text-gray-500">Please contact your administrator or create a new center</p>
        </div>
    </div>
@endsection
