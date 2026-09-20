@extends('center_panel.emails.automation.layout')

@section('heading', 'Your listing could use a refresh')
@section('lede', 'Small updates can make a big difference in visibility and trust.')

@section('content')
    <p style="font-size:14px; margin-bottom:14px;">Hello {{ $firstName }},</p>

    <p style="font-size:14px; margin-bottom:14px;">We noticed that your listing has not been updated in some time.</p>

    <p style="font-size:14px; margin-bottom:16px;">Travelers are more likely to trust listings that feel current and active. Refreshing even one area of your listing can make a noticeable difference.</p>

    <p style="font-size:14px; font-weight:bold; color:#0f172a; margin-bottom:10px;">Please consider updating:</p>

    @include('center_panel.emails.automation.partials.checklist', ['items' => [
        'Retreat details',
        'Availability',
        'Pricing',
        'Accommodation information',
        'Photos',
        'Seasonal offers',
    ]])

    <p style="font-size:14px; margin-bottom:0;">A small refresh today can help keep your center competitive.</p>
@endsection
