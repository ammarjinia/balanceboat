@extends('center_panel.emails.automation.layout')

@section('heading', 'Add a new retreat to keep your listing fresh')
@section('lede', 'New experiences help your center stay visible and relevant.')

@section('content')
    <p style="font-size:14px; margin-bottom:14px;">Hello {{ $firstName }},</p>

    <p style="font-size:14px; margin-bottom:14px;">Your center is doing well, and adding new retreats can help you attract even more interest.</p>

    <p style="font-size:14px; margin-bottom:16px;">Travelers love variety. When you publish new experiences, you create more reasons for them to explore your center again.</p>

    <p style="font-size:14px; font-weight:bold; color:#0f172a; margin-bottom:10px;">Consider adding:</p>

    @include('center_panel.emails.automation.partials.checklist', ['items' => [
        'Seasonal retreats',
        'Weekend wellness programs',
        'Detox or healing journeys',
        'Teacher training programs',
        'Special workshops or themed experiences',
    ]])

    <p style="font-size:14px; margin-bottom:0;">Fresh retreat listings help keep your center active and discoverable.</p>
@endsection
