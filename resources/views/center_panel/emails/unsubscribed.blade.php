{{-- Confirmation page shown after a partner clicks the unsubscribe link in an automation email. --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Unsubscribed — BalanceBoat Center Dashboard</title>
    <style>
        body { margin:0; background:#f4efed; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; color:#334155; }
        .shell { max-width:520px; margin:12vh auto; padding:0 16px; }
        .card { background:#ffffff; border-radius:14px; padding:36px 32px; box-shadow:0 1px 3px rgba(15,23,42,.08); }
        .brand { font-weight:700; color:#0f172a; letter-spacing:.02em; }
        .kicker { font-size:11px; text-transform:uppercase; letter-spacing:.08em; color:#a78bfa; margin-top:2px; }
        h1 { font-size:20px; color:#0f172a; margin:26px 0 10px; }
        p { font-size:14px; line-height:22px; margin:0 0 14px; }
        a { color:#c026d3; }
        .cta { display:inline-block; margin-top:8px; padding:12px 26px; background:#c026d3; color:#fff; border-radius:999px; font-size:14px; font-weight:700; text-decoration:none; }
        .note { font-size:12px; color:#64748b; margin-top:22px; }
    </style>
</head>
<body>
    <div class="shell">
        <div class="card">
            <div class="brand">BalanceBoat</div>
            <div class="kicker">Center Dashboard</div>

            <h1>You have been unsubscribed</h1>

            <p>{{ $center->name }} will no longer receive listing reminders, performance summaries or recommendation emails from BalanceBoat.</p>

            <p>You will still receive essential account messages, such as password resets and inquiry notifications, because your account needs them to work.</p>

            <p>Changed your mind, or unsubscribed by accident? Email <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a> and we will turn them back on.</p>

            <a class="cta" href="{{ route('center-panel.dashboard') }}">Go to Your Dashboard</a>

            <p class="note">Your listing stays live and fully visible to travelers &mdash; unsubscribing only affects email.</p>
        </div>
    </div>
</body>
</html>
