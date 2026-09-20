<?php

namespace App\Http\Controllers\Center;

use App\Centers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Unsubscribe handling for the center-panel email automation.
 *
 * Reached through a signed URL in every non-transactional email's footer, and through the
 * List-Unsubscribe header that SendCenterAutomationEmail attaches — which is why POST is routed
 * here too: RFC 8058 one-click unsubscribe sends a POST, and inbox providers will report the link
 * as broken if only GET is answered.
 *
 * Opting out suppresses listing reminders and recommendations. It deliberately does not suppress
 * transactional mail (password resets, the welcome email), which the partner needs in order to
 * use the account at all.
 */
class CenterEmailPreferencesController extends Controller
{
    public function unsubscribe(Request $request, $center)
    {
        $center = Centers::findOrFail($center);

        if (!$center->email_automation_opt_out) {
            $center->email_automation_opt_out    = 1;
            $center->email_automation_opt_out_at = now();
            $center->save();
        }

        // One-click unsubscribe clients expect a bare 200 and never render a page.
        if ($request->isMethod('post')) {
            return response()->noContent();
        }

        return view('center_panel.emails.unsubscribed', [
            'center'       => $center,
            'supportEmail' => config('center_emails.support_email'),
        ]);
    }
}
