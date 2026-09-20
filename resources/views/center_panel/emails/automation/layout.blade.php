{{--
    Shared layout for every center-panel automation email.

    Implements the standard structure from section 1 of the email copy/design document:
    header with wordmark + light navigation line, hero headline with one supporting sentence,
    body, a single primary CTA button, and a footer whose reassurance line varies by email type.

    Table-based with inline styles and a 560px shell — the same approach as the existing
    password_reset.blade.php, which is what renders correctly in Outlook and Gmail.

    Sections a child view provides:
      @section('heading')  hero headline
      @section('lede')     the one supporting sentence under it
      @section('content')  body paragraphs and bullet lists

    Variables injected by CenterEmailAutomationService::payload():
      $previewText $ctaLabel $ctaUrl $footerVariant $supportEmail $dashboardUrl $unsubscribeUrl
--}}
@php
    $footerLines = [
        'operational'  => 'Keeping your listing updated helps travelers trust your center and book with confidence.',
        'performance'  => 'Small updates can create stronger visibility and better booking outcomes.',
        'ai'           => 'These recommendations are based on recent listing activity and traveler demand patterns.',
    ];
    $footerLine = $footerLines[$footerVariant ?? 'operational'] ?? $footerLines['operational'];
@endphp
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:v="urn:schemas-microsoft-com:vml">
    <head>
        <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
        <meta content="telephone=no" name="format-detection" />
        <meta content="width=mobile-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;" name="viewport" />
        <title>@yield('heading')</title>
        <style type="text/css">
            body, #body-table { height:100% !important; width:100% !important; margin:0 auto; padding:0; line-height:100%; font-family:Arial, Helvetica, sans-serif; font-size:13px; }
            img, a img { border:0; outline:none; text-decoration:none; }
            table, td { border-collapse:collapse; }
            p { padding:0; margin:0; line-height:22px; font-family:Arial, Helvetica, sans-serif; }
            a, a:link { color:#7c3aed; text-decoration:none !important; }
            @media only screen and (max-width: 640px) {
                *[class].mobile-width { width: 440px !important; padding: 0 4px; }
                *[class].content-width { width: 360px !important; }
            }
            @media only screen and (max-width: 480px) {
                *[class].mobile-width { width: 100% !important; padding: 0 4px; }
                *[class].content-width { width: 100% !important; padding: 24px 20px !important; }
            }
        </style>
    </head>
    <body>
        {{-- Inbox preview text. Hidden in the body, shown next to the subject line in the list. --}}
        <div style="display:none; font-size:1px; color:#ffffff; line-height:1px; max-height:0; max-width:0; opacity:0; overflow:hidden;">
            {{ $previewText ?? '' }}
        </div>

        <table id="body-table" align="center" width="100%" bgcolor="#f4efed" cellspacing="0" cellpadding="0" border="0" style="table-layout:fixed;">
            <tbody>
                <tr>
                    <td valign="top" bgcolor="#f4efed" align="center" style="padding:30px 10px;">
                        <table width="560" bgcolor="#ffffff" align="center" cellspacing="0" cellpadding="0" border="0" class="mobile-width" style="border-radius:12px; overflow:hidden;">
                            <tbody>

                                {{-- Header --}}
                                <tr>
                                    <td align="center" style="background:#0f172a; padding:22px 20px 16px;">
                                        <span style="color:#ffffff; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:18px; letter-spacing:0.02em;">BalanceBoat</span>
                                        <div style="color:#a78bfa; font-size:11px; text-transform:uppercase; letter-spacing:0.08em; margin-top:4px;">Center Dashboard</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="background:#1e293b; padding:10px 20px; font-size:11px; color:#cbd5e1; letter-spacing:0.03em;">
                                        <a href="{{ route('center-panel.dashboard') }}" style="color:#cbd5e1 !important; padding:0 6px;">Dashboard</a>
                                        <span style="color:#475569;">|</span>
                                        <a href="{{ route('center-panel.experiences') }}" style="color:#cbd5e1 !important; padding:0 6px;">Retreats</a>
                                        <span style="color:#475569;">|</span>
                                        <a href="{{ route('center-panel.availability') }}" style="color:#cbd5e1 !important; padding:0 6px;">Availability</a>
                                        <span style="color:#475569;">|</span>
                                        <a href="{{ route('center-panel.commission') }}" style="color:#cbd5e1 !important; padding:0 6px;">Pricing</a>
                                    </td>
                                </tr>

                                {{-- Hero --}}
                                <tr>
                                    <td style="padding:32px 36px 0;" class="content-width">
                                        <p style="font-size:20px; line-height:28px; color:#0f172a; font-weight:bold; margin-bottom:8px;">@yield('heading')</p>
                                        <p style="font-size:14px; color:#64748b; margin-bottom:0;">@yield('lede')</p>
                                    </td>
                                </tr>

                                {{-- Body --}}
                                <tr>
                                    <td style="padding:22px 36px 8px; color:#334155;" class="content-width">
                                        @yield('content')
                                    </td>
                                </tr>

                                {{-- Primary CTA --}}
                                <tr>
                                    <td align="center" style="padding:8px 36px 34px;" class="content-width">
                                        <table align="center" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td align="center" style="background:#c026d3; border-radius:999px;">
                                                    <a href="{{ $ctaUrl }}" style="display:inline-block; padding:14px 34px; color:#ffffff !important; font-weight:bold; font-size:14px; font-family:Arial, Helvetica, sans-serif;">{{ $ctaLabel }}</a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                {{-- Footer --}}
                                <tr>
                                    <td style="background:#faf7f6; padding:22px 36px; border-top:1px solid #ece7e5;" class="content-width">
                                        <p style="font-size:12px; color:#64748b; margin-bottom:12px;">{{ $footerLine }}</p>
                                        <p style="font-size:12px; color:#64748b; margin-bottom:4px;">
                                            Need help? Contact us at <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a>
                                        </p>
                                        <p style="font-size:12px; color:#64748b; margin-bottom:0;">
                                            <a href="{{ $dashboardUrl }}">Open your Center Dashboard</a>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="background:#f4efed; padding:16px 20px; font-size:11px; color:#94a3b8;">
                                        &copy; {{ date('Y') }} BalanceBoat. All rights reserved.
                                        @if (!empty($unsubscribeUrl))
                                            <br><a href="{{ $unsubscribeUrl }}" style="color:#94a3b8 !important; text-decoration:underline !important;">Unsubscribe from listing reminders</a>
                                        @endif
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
