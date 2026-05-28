<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAccountRequest;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $center = $user->primary_center ?? $user->centers()->first();

        if (!$center) {
            return redirect()->route('dashboard')
                ->with('error', 'No center assigned to your account');
        }

        return view('dashboard.account', [
            'user' => $user,
            'center' => $center,
            'completion_percentage' => $center->completion_percentage,
            'ai_trust_score' => $this->calculateTrustScore($center),
            'security_checks' => $this->getSecurityStatus($user),
            'payout_accounts' => $center->payoutAccounts()->get(),
            'commission' => $center->commission,
        ]);
    }

    public function update(UpdateAccountRequest $request)
    {
        $center = auth()->user()->primary_center ?? auth()->user()->centers()->first();

        $center->update($request->safe([
            'name',
            'business_name',
            'contact_person',
            'year_of_foundation',
            'email_address',
            'contact_number',
            'whatsapp_number',
            'website',
            'facebook_url',
            'instagram_url',
            'address_of_center',
            'city',
            'country',
            'gst_number',
            'pan_number'
        ]));

        // Update or create payout account
        if ($request->has('account_number')) {
            $center->payoutAccounts()->updateOrCreate(
                ['center_id' => $center->id],
                [
                    'account_holder_name' => $request->account_holder_name,
                    'bank_name' => $request->bank_name,
                    'account_number' => $request->account_number,
                    'ifsc_code' => $request->ifsc_code,
                    'upi_id' => $request->upi_id,
                    'preferred_payout_cycle' => $request->preferred_payout_cycle,
                ]
            );
        }

        return back()->with('success', 'Account information updated successfully');
    }

    private function calculateTrustScore($center): int
    {
        $score = 0;

        // Profile completeness (max 40)
        $profileFields = ['name', 'about_center', 'banner_image_url', 'address_of_center'];
        $filled = collect($profileFields)->filter(fn($f) => !empty($center->{$f}))->count();
        $score += round(($filled / count($profileFields)) * 40);

        // Security (max 20)
        $user = $center->users()->first();
        if ($user && $user->email_verified_at) $score += 10;
        if ($center->gst_number && $center->pan_number) $score += 10;

        // Activity (max 20)
        if ($center->experiences()->count() > 0) $score += 10;
        if ($center->experiences()->with('bookings')->get()->sum(fn($e) => $e->bookings->count()) > 0) $score += 10;

        // Reputation (max 20)
        $avgRating = $center->average_rating;
        if ($avgRating >= 4.5) $score += 20;
        elseif ($avgRating >= 4.0) $score += 15;
        elseif ($avgRating >= 3.5) $score += 10;

        return min(100, $score);
    }

    private function getSecurityStatus($user)
    {
        return [
            'email_verified' => [
                'status' => $user->email_verified_at ? 'verified' : 'unverified',
                'date' => $user->email_verified_at?->format('M d, Y'),
                'action' => $user->email_verified_at ? 'Change' : 'Verify'
            ],
            'password_strength' => [
                'status' => 'strong',
                'last_changed' => now()->subDays(45)->format('M d, Y'),
                'action' => 'Update'
            ],
            'two_fa' => [
                'status' => 'disabled',
                'action' => 'Enable 2FA'
            ],
            'whatsapp' => [
                'status' => $user->primary_center?->whatsapp_number ? 'verified' : 'unverified',
                'action' => 'Verify'
            ]
        ];
    }
}
