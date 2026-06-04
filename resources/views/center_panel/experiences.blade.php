@extends('layouts.center')

@section('title', 'Center Experiences — BalanceBoat')

@section('content')
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <h1 class="text-3xl font-serif font-bold text-slate-900">Experiences</h1>
            <p class="text-slate-600 text-sm mt-1">Manage your center's experiences and offerings.</p>
        </div>
        <a href="{{ route('center-panel.dashboard') }}" class="px-4 py-2 bg-purple-600 text-white rounded-2xl text-xs font-semibold hover:bg-purple-700 transition-all inline-flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Dashboard
        </a>
    </div>

    <div class="glass rounded-3xl p-6 shadow-sm">
        @if($experiences && count($experiences) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200">
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">#</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">Experience Name</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">Category</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">Price</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">Status</th>
                            <th class="text-center py-3 px-4 font-semibold text-slate-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($experiences as $exp)
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-all">
                                <td class="py-3 px-4">{{ $exp->id }}</td>
                                <td class="py-3 px-4 font-medium text-slate-900">{{ $exp->name ?? 'N/A' }}</td>
                                <td class="py-3 px-4 text-xs text-slate-600">{{ $exp->duration ?? 'N/A' }}</td>
                                <td class="py-3 px-4 font-semibold">{{ $exp->currency ?? '$' }} {{ $exp->price ?? 'N/A' }}</td>
                                <td class="py-3 px-4">
                                    @if($exp->is_draft)
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-semibold">Draft</span>
                                    @else
                                        <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded text-xs font-semibold">Published</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <button class="text-purple-600 hover:underline text-xs">View</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($experiences->hasPages())
                <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 text-sm text-slate-600">
                    <p>Showing {{ $experiences->firstItem() }} to {{ $experiences->lastItem() }} of {{ $experiences->total() }} experiences</p>
                    <div class="flex flex-wrap gap-2">{{ $experiences->links() }}</div>
                </div>
            @endif
        @else
            <div class="text-center py-16">
                <i class="fa-solid fa-inbox text-4xl text-slate-300 mb-3"></i>
                <p class="text-slate-500">No experiences found for your center.</p>
            </div>
        @endif
    </div>
@endsection
