@extends('center_panel.emails.automation.layout')

@section('heading', 'Complete your center profile')
@section('lede', 'Add your photos, description, and facilities so travelers can understand your center better.')

@section('content')
    <p style="font-size:14px; margin-bottom:14px;">Hello {{ $firstName }},</p>

    <p style="font-size:14px; margin-bottom:14px;">Your center profile is almost ready, but a few important details are still missing.</p>

    <p style="font-size:14px; margin-bottom:18px;">Travelers often decide within seconds whether they want to explore a listing further. A complete profile helps your center look more credible, more polished, and more bookable.</p>

    {{-- Real completion figure, from the same calculation the dashboard renders, so the two agree. --}}
    <table cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-bottom:18px;">
        <tr>
            <td style="font-size:12px; color:#64748b; padding-bottom:6px;">Profile completeness &mdash; {{ $snapshot['profile_score'] }}%</td>
        </tr>
        <tr>
            <td>
                <table cellspacing="0" cellpadding="0" border="0" width="100%" style="background:#ece7e5; border-radius:999px;">
                    <tr>
                        <td width="{{ max($snapshot['profile_score'], 2) }}%" style="background:#c026d3; border-radius:999px; font-size:0; line-height:8px; height:8px;">&nbsp;</td>
                        <td style="font-size:0; line-height:8px;">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <p style="font-size:14px; font-weight:bold; color:#0f172a; margin-bottom:10px;">Please complete these sections:</p>

    @include('center_panel.emails.automation.partials.checklist', [
        'tone'  => 'missing',
        'items' => array_slice($snapshot['profile_missing'], 0, 6),
    ])

    <p style="font-size:14px; margin-bottom:0;">The better your profile looks, the more confidence travelers will have in your center.</p>
@endsection
