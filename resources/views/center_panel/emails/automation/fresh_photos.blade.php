@extends('center_panel.emails.automation.layout')

@section('heading', 'Fresh photos can improve traveler interest')
@section('lede', 'New visuals help your center feel current, inviting, and trustworthy.')

@section('content')
    <p style="font-size:14px; margin-bottom:14px;">Hello {{ $firstName }},</p>

    <p style="font-size:14px; margin-bottom:14px;">Your photos are one of the strongest tools you have for building trust.</p>

    <p style="font-size:14px; margin-bottom:16px;">If your gallery has not been updated in a while, this is a great time to add fresh images of your rooms, yoga spaces, wellness areas, meals, and surroundings.</p>

    <p style="font-size:14px; font-weight:bold; color:#0f172a; margin-bottom:10px;">Good photos help travelers:</p>

    @include('center_panel.emails.automation.partials.checklist', ['items' => [
        'Imagine the experience',
        'Trust the quality of your center',
        'Feel more confident booking',
        'Spend more time on your listing',
    ]])

    <p style="font-size:14px; margin-bottom:0;">A visual refresh can make a noticeable difference.</p>
@endsection
