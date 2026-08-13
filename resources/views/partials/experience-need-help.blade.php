{{-- New section (Task 12) — Requirement 15. No standalone "Need Help?" card existed inline in
     experience_detail.blade.php before this; center contact fields were only ever shown inside the
     About Center card, and the "Request Call Back"/"Check Availability" triggers only existed in the
     layout's header widget and the booking sidebar. This partial surfaces the same existing $center
     contact data and reuses the existing modal trigger attributes verbatim (data-popup="requstcallPopup",
     data-popup="checkAvailability", class="show-bg-modal") so the layout-level modal JS keeps binding them
     — no new backend, no new modal, no new inquiry flow. --}}
@if(@$center->email_address || @$center->contact_number || @$center->whatsapp_number || @$center->website)
<div class="xd-card" id="need-help">
    <span class="xd-tag">We're Here to Help</span>
    <h2 class="xd-title"><span class="xd-title-icon">&#128172;</span> Need Help?</h2>
    <div class="xd-help-card">
        <div>
            <p class="mb-2">Have a question before you book? Reach out directly or check live availability.</p>
            <div class="xd-help-contact-row">
                @if(@$center->email_address)
                <a class="xd-help-contact-item" href="mailto:{{ @$center->email_address }}"><span class="icon-mail"></span> {{ @$center->email_address }}</a>
                @endif
                @if(@$center->contact_number)
                <a class="xd-help-contact-item" href="tel:{{ @$center->contact_number }}"><span class="icon-phone"></span> {{ @$center->contact_number }}</a>
                @endif
                @if(@$center->whatsapp_number)
                <a class="xd-help-contact-item" href="https://wa.me/{{ preg_replace('/\D+/', '', @$center->whatsapp_number) }}" target="_blank"><span class="icon-whatsapp"></span> WhatsApp</a>
                @endif
                @if(@$center->website)
                <a class="xd-help-contact-item" target="_blank" href="{{ @$center->website }}"><span class="icon-globe"></span> Website</a>
                @endif
            </div>
        </div>
    </div>
    <div class="d-flex flex-wrap mt-3" style="gap:10px;">
        <button type="button" data-popup="requstcallPopup" class="show-bg-modal xd-btn-outline xd-btn-sm">Request Call Back</button>
        <button type="button" data-popup="checkAvailability" class="show-bg-modal xd-btn-outline xd-btn-sm">Check Dates &amp; Availability</button>
    </div>
</div>
@endif
