@extends('center_panel.emails.automation.layout')

@section('heading', 'Add your first retreat')
@section('lede', 'Retreat listings help travelers understand what you offer and why they should choose you.')

@section('content')
    <p style="font-size:14px; margin-bottom:14px;">Hello {{ $firstName }},</p>

    <p style="font-size:14px; margin-bottom:14px;">We noticed that your center has not yet added a retreat.</p>

    <p style="font-size:14px; margin-bottom:16px;">This is one of the most important steps in getting discovered on BalanceBoat. Retreat listings help travelers understand what kind of experience you offer, when it happens, what is included, and why it is valuable.</p>

    <p style="font-size:14px; font-weight:bold; color:#0f172a; margin-bottom:10px;">A strong retreat listing should include:</p>

    @include('center_panel.emails.automation.partials.checklist', ['items' => [
        'Retreat title',
        'Duration',
        'Program overview',
        'Inclusions',
        'Dates or seasonal schedule',
        'Pricing',
    ]])

    <p style="font-size:14px; margin-bottom:0;">Once your first retreat is live, your center becomes much easier to search, compare, and book.</p>
@endsection
