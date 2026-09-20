@extends('center_panel.emails.automation.layout')

@section('heading', 'Your retreat has very few open dates left')
@section('lede', 'Opening more availability can help you capture more inquiries.')

@section('content')
    <p style="font-size:14px; margin-bottom:14px;">Hello {{ $firstName }},</p>

    <p style="font-size:14px; margin-bottom:14px;">We noticed that your future availability is getting limited.</p>

    <table cellspacing="0" cellpadding="0" border="0" width="100%" style="background:#fff7ed; border-left:3px solid #f59e0b; border-radius:6px; margin-bottom:16px;">
        <tr>
            <td style="padding:12px 14px; font-size:13px; color:#92400e;">
                {{ $snapshot['open_slots_ahead'] }}
                bookable {{ \Illuminate\Support\Str::plural('date', $snapshot['open_slots_ahead']) }}
                in the next {{ config('center_emails.thresholds.low_availability_horizon_days') }} days
            </td>
        </tr>
    </table>

    <p style="font-size:14px; margin-bottom:16px;">When a retreat has very few open dates left, it can reduce your chances of appearing attractive to travelers who are actively comparing options. Updating your calendar can help you keep your listing active and bookable.</p>

    <p style="font-size:14px; font-weight:bold; color:#0f172a; margin-bottom:10px;">Please consider:</p>

    @include('center_panel.emails.automation.partials.checklist', ['items' => [
        'Opening new dates',
        'Adding more retreat sessions',
        'Extending seasonal availability',
        'Reviewing room inventory',
    ]])

    <p style="font-size:14px; margin-bottom:0;">A few quick updates can help improve your booking potential.</p>
@endsection
