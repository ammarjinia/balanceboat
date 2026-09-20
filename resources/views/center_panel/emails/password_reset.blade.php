<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:v="urn:schemas-microsoft-com:vml">
    <head>
        <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
        <meta content="telephone=no" name="format-detection" />
        <meta content="width=mobile-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;" name="viewport" />
        <title>Reset your BalanceBoat Center password</title>
        <style type="text/css">
            body, #body-table { height:100% !important; width:100% !important; margin:0 auto; padding:0; line-height:100%; font-family:Arial, Helvetica, sans-serif; font-size:13px; }
            img, a img { border:0; outline:none; text-decoration:none; }
            table, td { border-collapse:collapse; }
            p { padding:0; margin:0; line-height:22px; font-family: Arial, Helvetica, sans-serif; }
            a, a:link { color:#7c3aed; text-decoration:none !important; }
            @media only screen and (max-width: 640px) {
                *[class].mobile-width { width: 440px !important; padding: 0 4px; }
                *[class].content-width { width: 360px !important; }
            }
        </style>
    </head>
    <body>
        <table id="body-table" align="center" width="100%" bgcolor="#e8e8e8" cellspacing="0" cellpadding="0" border="0" style="table-layout:fixed;">
            <tbody>
                <tr>
                    <td valign="top" bgcolor="#e8e8e8" align="center" style="padding:30px 10px;">
                        <table width="560" bgcolor="#ffffff" align="center" cellspacing="0" cellpadding="0" border="0" class="mobile-width" style="border-radius:12px; overflow:hidden;">
                            <tbody>
                                <tr>
                                    <td align="center" style="background:#0f172a; padding:22px 20px;">
                                        <span style="color:#ffffff; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:16px; letter-spacing:0.02em;">BalanceBoat</span>
                                        <div style="color:#a78bfa; font-size:11px; text-transform:uppercase; letter-spacing:0.08em; margin-top:4px;">Center Management System</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:32px 36px;" class="content-width">
                                        <p style="font-size:15px; color:#0f172a; font-weight:bold; margin-bottom:12px;">Reset your password</p>
                                        <p style="margin-bottom:14px;">Hi {{ trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'there' }},</p>
                                        <p style="margin-bottom:14px;">We received a request to reset the password for your Center Owner account ({{ $user->email }}). Click the button below to choose a new password.</p>
                                        <table align="center" cellspacing="0" cellpadding="0" border="0" style="margin:24px auto;">
                                            <tr>
                                                <td align="center" style="background:#7c3aed; border-radius:10px;">
                                                    <a href="{{ $resetUrl }}" style="display:inline-block; padding:12px 28px; color:#ffffff !important; font-weight:bold; font-size:13px; font-family:Arial, Helvetica, sans-serif;">Reset Password</a>
                                                </td>
                                            </tr>
                                        </table>
                                        <p style="margin-bottom:14px; font-size:12px; color:#64748b;">This link will expire in {{ $expireMinutes }} minutes. If you didn't request a password reset, you can safely ignore this email — your password will remain unchanged.</p>
                                        <p style="margin-bottom:0; font-size:12px; color:#64748b; word-break:break-all;">If the button doesn't work, copy and paste this link into your browser:<br><a href="{{ $resetUrl }}">{{ $resetUrl }}</a></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="background:#f4efed; padding:16px 20px; font-size:11px; color:#94a3b8;">
                                        &copy; {{ date('Y') }} BalanceBoat. All rights reserved.
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
