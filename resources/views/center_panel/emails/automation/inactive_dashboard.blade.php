@extends('center_panel.emails.automation.layout')

@section('heading', 'We have not seen you in your dashboard recently')
@section('lede', 'Log in to review your listing and keep your information current.')

@section('content')
    <p style="font-size:14px; margin-bottom:14px;">Hello {{ $firstName }},</p>

    <p style="font-size:14px; margin-bottom:14px;">It looks like you have not logged in to your Center Dashboard recently.</p>

    <p style="font-size:14px; margin-bottom:16px;">A quick review can help you keep your profile current and make sure travelers see the right information. Even a short update can improve trust and performance.</p>

    <p style="font-size:14px; font-weight:bold; color:#0f172a; margin-bottom:10px;">We recommend checking:</p>

    @include('center_panel.emails.automation.partials.checklist', ['items' => [
        'Your profile',
        'Retreat listings',
        'Availability',
        'Pricing',
        'Photos',
        'Commission details',
    ]])

    <p style="font-size:14px; margin-bottom:0;">A refreshed listing is always stronger than a stale one.</p>
@endsection
