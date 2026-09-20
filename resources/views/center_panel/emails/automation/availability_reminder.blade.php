@extends('center_panel.emails.automation.layout')

@section('heading', 'Your availability calendar needs a refresh')
@section('lede', 'Keep your retreat bookable by updating future dates and open slots.')

@section('content')
    <p style="font-size:14px; margin-bottom:14px;">Hello {{ $firstName }},</p>

    <p style="font-size:14px; margin-bottom:14px;">We wanted to remind you that your availability calendar has not been updated recently.</p>

    @if ($snapshot['availability_updated_at'])
        <table cellspacing="0" cellpadding="0" border="0" width="100%" style="background:#fff7ed; border-left:3px solid #f59e0b; border-radius:6px; margin-bottom:16px;">
            <tr>
                <td style="padding:12px 14px; font-size:13px; color:#92400e;">
                    Last calendar update: {{ $snapshot['availability_updated_at']->format('j F Y') }}
                    ({{ (int) $snapshot['availability_updated_at']->diffInDays(now()) }} days ago)
                </td>
            </tr>
        </table>
    @endif

    <p style="font-size:14px; margin-bottom:16px;">An outdated calendar can lead to missed inquiries or confusion for travelers. Regular updates help keep your listing accurate and easy to book.</p>

    <p style="font-size:14px; font-weight:bold; color:#0f172a; margin-bottom:10px;">Please log in and confirm:</p>

    @include('center_panel.emails.automation.partials.checklist', ['items' => [
        'Open dates',
        'Closed dates',
        'Upcoming retreat schedules',
        'Seasonal changes',
        'Any temporary changes in room availability',
    ]])

    <p style="font-size:14px; margin-bottom:0;">A current calendar helps travelers trust your listing more quickly.</p>
@endsection
