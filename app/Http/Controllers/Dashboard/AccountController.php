<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\Center;
use App\Models\PayoutAccount;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $center = $user->getPrimaryCenter();

        if (!$center) {
            return view('dashboard.no-center');
        }

        $aiTrustScore = $this->calculateAITrustScore($center);
        $completionPercentage = $center->completion_percentage;
        $payoutAccount = $center->payoutAccounts()->first();

        return view('dashboard.account', [
            'center' => $center,
            'user' => $user,
            'ai_trust_score' => $aiTrustScore,
            'completion_percentage' => $completionPercentage,
            'payout_account' => $payoutAccount,
        ]);
    }

    public function update(UpdateAccountRequest $request)
    {
        $user = Auth::user();
        $center = $user->getPrimaryCenter();

        if (!$center) {
            return redirect()->back()->with('error', 'Center not found');
        }

        // Update center
        $center->update($request->validated());

        // Update or create payout account
        if ($request->has('account_holder_name')) {
            PayoutAccount::updateOrCreate(
                ['center_id' => $center->id],
                [
                    'account_holder_name' => $request->input('account_holder_name'),
                    'bank_name' => $request->input('bank_name'),
                    'account_number' => $request->input('account_number'),
                    'ifsc_code' => $request->input('ifsc_code'),
                    'preferred_payout_cycle' => $request->input('preferred_payout_cycle'),
                    'upi_id' => $request->input('upi_id'),
                ]
            );
        }

        return redirect()->back()->with('success', 'Account information updated successfully!');
    }

    private function calculateAITrustScore(Center $center): array
    {
        $score = 0;
        $maxScore = 100;

        // Profile completeness (40 points)
        $profileFields = ['name', 'about_center', 'email_address', 'contact_number'];
        $filledFields = collect($profileFields)->filter(fn($field) => !empty($center->{$field}))->count();
        $score += ($filledFields / count($profileFields)) * 40;

        // Bookings & reviews (30 points)
        $totalBookings = $center->experiences()
            ->with('bookings')
            ->get()
            ->sum(fn($e) => $e->bookings->where('order_status', 'confirmed')->count());
        $averageRating = $center->reviews()->avg('rating') ?? 0;

        $score += min(($totalBookings / 10) * 15, 15);
        $score += ($averageRating / 5) * 15;

        // Security (20 points)
        $score += 10; // Email verified
        if (!empty($center->gst_number)) $score += 5;
        if ($center->payoutAccounts()->exists()) $score += 5;

        // Content (10 points)
        if (!empty($center->banner_image_url)) $score += 5;
        if ($center->galleries()->exists()) $score += 5;

        $finalScore = min(round($score), 100);
        $status = match (true) {
            $finalScore >= 80 => 'Very Good',
            $finalScore >= 60 => 'Good',
            $finalScore >= 40 => 'Fair',
            default => 'Poor'
        };

        return [
            'score' => $finalScore,
            'status' => $status,
            'out_of' => $maxScore,
        ];
    }
}
