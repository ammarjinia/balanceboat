@extends('center_panel.emails.automation.layout')

@section('heading', 'Add your accommodation details')
@section('lede', 'Room types, occupancy, and amenities help travelers make faster decisions.')

@section('content')
    <p style="font-size:14px; margin-bottom:14px;">Hello {{ $firstName }},</p>

    <p style="font-size:14px; margin-bottom:14px;">Your retreat is live, and now it is time to complete the accommodation details.</p>

    <p style="font-size:14px; margin-bottom:16px;">Travelers want to know where they will stay, what type of room they will have, and what amenities are included. Clear accommodation information reduces confusion and improves booking confidence.</p>

    <p style="font-size:14px; font-weight:bold; color:#0f172a; margin-bottom:10px;">Please add:</p>

    @include('center_panel.emails.automation.partials.checklist', ['items' => [
        'Room types',
        'Occupancy options',
        'Bed configuration',
        'Private or shared stay',
        'Bathroom details',
        'Photos of rooms and common areas',
    ]])

    <p style="font-size:14px; margin-bottom:0;">Better accommodation details can directly improve conversion.</p>
@endsection
