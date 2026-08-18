{{-- Shared "Start Date" field for the desktop sidebar and mobile drawer booking forms (both
     previously had this block inlined, verbatim identical). Same field name/classes as before
     (qb-date, bkdate) so xdInitQuickBooking's calculatePrice() keeps binding unchanged — only the
     interaction changed: instead of the browser's native date input (onfocus type-swap hack), this
     is a readonly text field that opens the rd-cal-popup calendar built by rdBuildCalendar() in the
     footer script, colored from the same $experience_upcoming_availability data as the Availability
     section and the "Check Availability" modal. --}}
<div class="xd-form-group rd-datefield-group">
    <label class="xd-form-label">Start Date</label>
    <input type="text" class="xd-input qb-date bkdate rd-date-input" name="booking_date" value="" placeholder="Select a date" readonly autocomplete="off" />
    <div class="rd-cal-popup" role="dialog" aria-label="Choose a start date"></div>
</div>
