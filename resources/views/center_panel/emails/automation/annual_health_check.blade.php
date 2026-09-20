@extends('center_panel.emails.automation.layout')

@section('heading', 'It is time for your annual listing review')
@section('lede', 'Review your center profile from top to bottom and keep it fully updated.')

@section('content')
    <p style="font-size:14px; margin-bottom:14px;">Hello {{ $firstName }},</p>

    <p style="font-size:14px; margin-bottom:14px;">It is time for your annual listing health check.</p>

    <p style="font-size:14px; margin-bottom:16px;">This is the perfect moment to review your center from the traveler&rsquo;s point of view and make sure every detail is accurate, attractive, and current.</p>

    <p style="font-size:14px; font-weight:bold; color:#0f172a; margin-bottom:10px;">Please review:</p>

    @include('center_panel.emails.automation.partials.checklist', ['items' => [
        'Center profile',
        'Retreat information',
        'Accommodation details',
        'Availability calendar',
        'Pricing',
        'Photos',
        'Commission settings',
        'Contact details',
    ]])

    <p style="font-size:14px; margin-bottom:0;">A yearly refresh keeps your listing aligned with the way your center actually operates today.</p>
@endsection
