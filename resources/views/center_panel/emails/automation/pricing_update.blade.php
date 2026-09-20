@extends('center_panel.emails.automation.layout')

@section('heading', 'Review your pricing')
@section('lede', 'Updated pricing helps your listing stay relevant for the current season.')

@section('content')
    <p style="font-size:14px; margin-bottom:14px;">Hello {{ $firstName }},</p>

    <p style="font-size:14px; margin-bottom:14px;">We recommend reviewing your pricing regularly to keep your retreat competitive and aligned with demand.</p>

    <p style="font-size:14px; margin-bottom:16px;">Travelers compare options quickly, and pricing is often one of the first things they notice. Even a small update can make your listing more attractive.</p>

    @if ($snapshot['pricing_updated_at'])
        <p style="font-size:13px; color:#64748b; margin-bottom:16px;">Your prices were last updated on {{ $snapshot['pricing_updated_at']->format('j F Y') }}.</p>
    @endif

    <p style="font-size:14px; font-weight:bold; color:#0f172a; margin-bottom:10px;">Please review:</p>

    @include('center_panel.emails.automation.partials.checklist', ['items' => [
        'Retreat pricing',
        'Room pricing',
        'Seasonal pricing',
        'Couple pricing',
        'Special offer pricing',
    ]])

    <p style="font-size:14px; margin-bottom:0;">If your pricing has changed recently, updating it in the dashboard will help avoid confusion and support better inquiries.</p>
@endsection
