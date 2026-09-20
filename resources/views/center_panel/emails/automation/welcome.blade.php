@extends('center_panel.emails.automation.layout')

@section('heading', 'Welcome to BalanceBoat')
@section('lede', 'Your Center Dashboard is live. Here is how to get set up for more visibility and bookings.')

@section('content')
    <p style="font-size:14px; margin-bottom:14px;">Hello {{ $firstName }},</p>

    <p style="font-size:14px; margin-bottom:14px;">Welcome to BalanceBoat. We are excited to have {{ $centerName }} on the platform.</p>

    <p style="font-size:14px; margin-bottom:16px;">Your Center Dashboard is now live, and this is where you can manage everything that helps travelers discover and book your center. The more complete your information is, the better your listing can perform.</p>

    <p style="font-size:14px; font-weight:bold; color:#0f172a; margin-bottom:10px;">Here&rsquo;s what you can do from the dashboard:</p>

    @include('center_panel.emails.automation.partials.checklist', ['items' => [
        'Update your center profile',
        'Add retreats and wellness experiences',
        'Manage accommodation details',
        'Publish availability and pricing',
        'Review commission settings',
    ]])

    <p style="font-size:14px; margin-bottom:0;">A complete and updated profile helps travelers trust your center faster and makes it easier for them to choose you.</p>
@endsection
