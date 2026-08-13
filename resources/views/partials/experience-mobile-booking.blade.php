{{-- Extracted from experience_detail.blade.php's original inline "Mobile persistent booking bar" +
     "Mobile full booking drawer" blocks (Task 15). Mechanical move of DISPLAY markup only — form field
     names/IDs, .qb-* class hooks, the <1024px xd-mobile-bar threshold, and xdOpenDrawer()/xdCloseDrawer()
     bindings are all verbatim unchanged.
     $offerActive is read here, not computed here — it was already kept in the parent view during Task 14
     (because this section also needs it), so no new scope-leak risk is introduced by this extraction. --}}
<div class="xd-mobile-bar">
    <div class="xd-mobile-bar-top">
        @if($offerActive)
        <span class="xd-mobile-discount-tag">&#128293; Limited-Time Offer Available</span>
        @else
        <span class="xd-mobile-discount-tag xd-mobile-discount-tag--plain">Flexible Dates Available</span>
        @endif
    </div>
    <div class="xd-mobile-bar-bottom">
        <button type="button" class="xd-btn-mobile-outline show-bg-modal" data-popup="checkAvailability">Check Dates</button>
        <button type="button" class="xd-btn-mobile-gradient" onclick="xdOpenDrawer()">Reserve Now</button>
    </div>
</div>

<div class="xd-mobile-drawer-overlay" id="xd-mobile-drawer-overlay">
    <div class="xd-mobile-drawer">
        <div class="xd-drawer-header">
            <h3 class="xd-booking-title" style="justify-content:flex-start;">Reserve This Retreat</h3>
            <button type="button" class="xd-modal-close" onclick="xdCloseDrawer()">&#10005;</button>
        </div>

        <form id="frmBookingMobile" name="frmBookingMobile" action="{{ url('/reservation') }}" method="POST" novalidate="novalidate" class="quick-booking">
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

            @if(@$experience->is_draft == 0 && @$experience->is_bookable == 1)
            <button type="submit" class="xd-btn-gradient qb-reserve-btn" disabled="disabled">
                Book Now
            </button>
            @endif
        </form>
    </div>
</div>
