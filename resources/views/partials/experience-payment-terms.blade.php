{{-- Extracted from experience_detail.blade.php's original inline "Payment & Cancellation Terms" +
     "Cancellation Policy" blocks (Task 11), merged under one reference-aligned section per design.md.
     Mechanical move only: same $experience/$center_commission/$site_currency variables, same fallback
     precedence (experience-level fields first, center_commission second), same @if guards. All local
     variables ($depositPolicy etc.) confirmed used only within this block — no scope-leak risk. --}}
@php
    $depositPolicy = @$experience->deposit_policy ?: @$center_commission->deposit_policy;
    $depositAmount = @$experience->deposit_amount ?: @$center_commission->deposit_amount;
    $cancelCondition = @$experience->cancellation_policy_condition ?: @$center_commission->cancellation_policy_condition;
    $cancelDays = @$experience->cancellation_policy_days ?: @$center_commission->cancellation_policy_days;
    $restOfPayment = @$experience->rest_of_payment ?: @$center_commission->rest_of_payment;
    $restOfPaymentDays = @$experience->rest_of_payment_days ?: @$center_commission->rest_of_payment_days;
    $taxInfo = @$experience->tax ?: @$center_commission->tax;
@endphp
@if($depositPolicy || $cancelCondition || $restOfPayment || $taxInfo)
<div class="xd-card" id="payment-terms">
    <span class="xd-tag">Fine Print</span>
    <h2 class="xd-title"><span class="xd-title-icon">&#128179;</span> Payment &amp; Cancellation Terms</h2>
    <ul class="bg-list-icon">
        @if($depositPolicy && $depositAmount)
        <li>A deposit of {{ \App\Http\Helpers\CommonHelper::get_currency_rate($depositAmount, $site_currency) }} is required to confirm booking.</li>
        @endif
        @if($restOfPayment && $restOfPaymentDays)
        <li>Balance payment is due {{ $restOfPaymentDays }} days before the retreat start date.</li>
        @endif
        @if($cancelCondition && $cancelDays)
        <li>Cancellations must be made at least {{ $cancelDays }} days in advance as per the cancellation policy.</li>
        @endif
        @if($taxInfo)
        <li>Applicable Tax: {{ $taxInfo }}</li>
        @endif
    </ul>
</div>
@endif

@if(@$experience->cancellation_policy)
<div class="xd-card" id="cancellation">
    <span class="xd-tag">Please Note</span>
    <h2 class="xd-title"><span class="xd-title-icon">&#128220;</span> Cancellation Policy</h2>
    <div>{!! @$experience->cancellation_policy !!}</div>
</div>
@endif
