@extends('center_panel.emails.automation.layout')

@section('heading', 'Set your availability calendar')
@section('lede', 'An updated calendar helps avoid missed opportunities and outdated listings.')

@section('content')
    <p style="font-size:14px; margin-bottom:14px;">Hello {{ $firstName }},</p>

    <p style="font-size:14px; margin-bottom:14px;">Your center listing looks stronger when travelers can see exactly when you are available.</p>

    <p style="font-size:14px; margin-bottom:16px;">An updated availability calendar helps prevent confusion, supports faster booking decisions, and keeps your listing active in search.</p>

    <p style="font-size:14px; font-weight:bold; color:#0f172a; margin-bottom:10px;">Please review and update:</p>

    @include('center_panel.emails.automation.partials.checklist', ['items' => [
        'Open dates',
        'Full dates',
        'Seasonal availability',
        'Retreat start and end dates',
        'Blackout periods or closure dates',
    ]])

    <p style="font-size:14px; margin-bottom:0;">Keeping availability current is one of the fastest ways to improve listing performance.</p>
@endsection
