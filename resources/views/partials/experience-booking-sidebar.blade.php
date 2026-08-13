{{-- Extracted from experience_detail.blade.php's original inline "Desktop Sticky Booking Sidebar" block
     (Task 14). Mechanical move of DISPLAY markup only — form field names/IDs, .qb-* class hooks, and the
     .xd-sidebar sticky mechanism are all verbatim unchanged so xdInitQuickBooking/xdSelectRoom (Task 2/7 JS)
     keep binding exactly as before.
     $offerActive is intentionally still computed in the parent view (not here): the mobile bottom bar
     (still inline until Task 15, then its own partial) also reads $offerActive, and Blade @include does not
     leak variables set inside a partial back to the parent scope — same class of risk already caught in
     Tasks 3 and 7. This partial only reads: $experience, $site_currency, $offerActive — all already defined
     by the parent before this include runs. --}}
<aside class="xd-sidebar">
    <div class="xd-booking-card" id="booking-card">
        <div class="xd-booking-header">
            @if($offerActive)
            <span class="xd-discount-tag">
                &#128293; {{ @$experience->offer_discount_type == 'amt' ? \App\Http\Helpers\CommonHelper::get_currency_rate(@$experience->offer_discount, $site_currency) : @$experience->offer_discount.'%' }} OFF &middot; Ends {{ \Carbon\Carbon::parse($experience->offer_end_date)->format('d M') }}
            </span>
            @endif
            <h3 class="xd-booking-title">Reserve This Retreat</h3>
        </div>

        <form id="frmBookingDesktop" name="frmBookingDesktop" action="{{ url('/reservation') }}" method="POST" novalidate="novalidate" class="quick-booking">
            {{ csrf_field() }}
            <input type="hidden" name="hdn_experience_id" class="qb-exp-id" value="{{ @$experience->id }}" />

            @include('partials.experience-booking-fields')

            <div class="xd-form-group">
                <label class="xd-form-label">Start Date</label>
                <input type="text" class="xd-input qb-date bkdate" name="booking_date" value="" min="<?php echo date("Y-m-d");?>" onfocus="(this.type = 'date')" placeholder="Select a date" />
            </div>

            <div class="xd-calc-box">
                <div class="xd-calc-row">
                    <span>Price</span>
                    <span class="qb-price">-</span>
                </div>
                <div class="xd-calc-row total">
                    <span>Booking Amount</span>
                    <span class="qb-booking-amount">-</span>
                </div>
            </div>

            <button type="button" class="xd-btn-outline show-bg-modal" data-popup="checkAvailability">
                Check Dates &amp; Availability
            </button>

            @if(@$experience->is_draft == 0 && @$experience->is_bookable == 1)
            <button type="submit" class="xd-btn-gradient qb-reserve-btn" disabled="disabled">
                Book Now
            </button>
            @endif
        </form>

        <div id="razorpay-affordability-widget" class="mt-3" style="margin:auto; text-align:center;"></div>
    </div>
</aside>
