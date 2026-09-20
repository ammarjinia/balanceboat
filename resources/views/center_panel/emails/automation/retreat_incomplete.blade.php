@extends('center_panel.emails.automation.layout')

@section('heading', 'A few retreat details are still missing')
@section('lede', 'Complete the missing fields so your retreat can perform better in search.')

@section('content')
    <p style="font-size:14px; margin-bottom:14px;">Hello {{ $firstName }},</p>

    <p style="font-size:14px; margin-bottom:14px;">
        @if (!empty($retreatName))
            Your retreat <strong style="color:#0f172a;">{{ $retreatName }}</strong> is almost ready, but a few details are still missing.
        @else
            Your retreat is almost ready, but a few details are still missing.
        @endif
    </p>

    <p style="font-size:14px; margin-bottom:16px;">Completing all required fields helps travelers understand your experience better and gives your listing a stronger chance of being explored and booked.</p>

    <p style="font-size:14px; font-weight:bold; color:#0f172a; margin-bottom:10px;">Please review and complete:</p>

    {{-- The actual missing fields for this retreat, not a generic list — the point of sending this
         one immediately is that we know exactly what is absent. --}}
    @include('center_panel.emails.automation.partials.checklist', [
        'tone'  => 'missing',
        'items' => $missingFields ?? [],
    ])

    <p style="font-size:14px; margin-bottom:0;">Once these details are complete, your retreat will be much stronger in search and more persuasive to travelers.</p>
@endsection
