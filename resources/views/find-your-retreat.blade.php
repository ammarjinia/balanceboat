@extends('layouts.front')

@section('title', 'Find Your Perfect Retreat — BalanceBoat')
@section('meta_title', 'Find Your Perfect Retreat — BalanceBoat')
@section('description', 'Answer 3 quick questions and let our wellness experts hand-pick the ideal yoga, Ayurveda, detox, or meditation retreat for you. Free & no commitment.')
@section('keywords', 'find yoga retreat, ayurveda retreat finder, wellness retreat recommendation, best detox retreat india, personalised retreat search')

@section('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
    /* ── Page Reset & Base ───────────────────────────────── */
    .fyr-page { min-height: 100vh; background: #FAFAFA; font-family: 'Inter', sans-serif; }

    /* ── Hero Strip ──────────────────────────────────────── */
    .fyr-hero {
        background: linear-gradient(150deg, #fff5f5 0%, #ffe9eb 55%, #fff9f0 100%);
        border-bottom: 1px solid rgba(230,57,70,0.12);
        padding: 64px 20px 80px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .fyr-hero::before {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(ellipse 70% 60% at 50% 0%, rgba(230,57,70,0.07) 0%, transparent 70%);
        pointer-events: none;
    }
    .fyr-hero-badge {
        display: inline-flex; align-items: center; gap: 7px;
        background: rgba(230,57,70,0.08); border: 1px solid rgba(230,57,70,0.2);
        border-radius: 100px; padding: 6px 16px;
        font-size: 11.5px; font-weight: 500; color: #E63946;
        letter-spacing: 0.05em; margin-bottom: 22px;
    }
    .fyr-hero h1 {
        font-family: 'Playfair Display', serif;
        font-size: clamp(32px, 5vw, 52px);
        font-weight: 700; color: #1A1A1A;
        line-height: 1.18; margin: 0 0 16px;
    }
    .fyr-hero h1 em { color: #E63946; font-style: italic; }
    .fyr-hero p {
        font-size: 16px; color: #555; font-weight: 300;
        max-width: 520px; margin: 0 auto 32px; line-height: 1.7;
    }
    .fyr-trust-row {
        display: flex; flex-wrap: wrap; gap: 12px;
        justify-content: center; margin-top: 12px;
    }
    .fyr-trust-chip {
        display: flex; align-items: center; gap: 6px;
        background: #fff; border: 1px solid rgba(0,0,0,0.08);
        border-radius: 100px; padding: 7px 14px;
        font-size: 12px; color: #555; white-space: nowrap;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }

    /* ── Form Card ───────────────────────────────────────── */
    .fyr-card-wrap {
        max-width: 580px; margin: -48px auto 60px;
        padding: 0 16px; position: relative; z-index: 2;
    }
    .fyr-card {
        background: #fff;
        border: 1px solid rgba(230,57,70,0.15);
        border-radius: 24px;
        box-shadow: 0 24px 64px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    /* ── Form Header ─────────────────────────────────────── */
    .fyr-form-header {
        background: linear-gradient(155deg, #FFF5F5 0%, #FFE9EB 100%);
        border-bottom: 1px solid rgba(230,57,70,0.12);
        padding: 28px 32px 22px;
    }
    .fyr-form-header h2 {
        font-family: 'Playfair Display', serif;
        font-size: 24px; font-weight: 700; color: #1A1A1A;
        line-height: 1.25; margin: 0 0 5px;
    }
    .fyr-form-header h2 em { color: #E63946; font-style: italic; }
    .fyr-form-header p { font-size: 12px; color: #666; font-weight: 300; margin: 0; letter-spacing: 0.025em; }

    /* Progress pips */
    .fyr-pips { display: flex; gap: 6px; margin-top: 18px; }
    .fyr-pip { height: 3px; flex: 1; border-radius: 2px; background: rgba(0,0,0,0.08); transition: background 0.4s ease; }
    .fyr-pip.active { background: #E63946; }
    .fyr-step-lbl { font-size: 10.5px; color: #999; margin-top: 7px; letter-spacing: 0.07em; }

    /* ── Form Body ───────────────────────────────────────── */
    .fyr-form-body { padding: 26px 32px 30px; }

    /* Section labels */
    .fyr-section-label {
        font-size: 10px; font-weight: 500; letter-spacing: 0.18em;
        text-transform: uppercase; color: #E63946;
        margin-bottom: 11px; display: flex; align-items: center; gap: 10px;
    }
    .fyr-section-label::after { content: ''; flex: 1; height: 1px; background: rgba(230,57,70,0.2); }

    /* Tile grid (retreat type) */
    .fyr-tile-grid {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px;
    }
    .fyr-tile {
        padding: 14px 8px 12px; border: 1.5px solid rgba(0,0,0,0.1);
        border-radius: 13px; background: #F5F5F5; color: #555;
        font-size: 11.5px; cursor: pointer; text-align: center;
        transition: all 0.2s ease; line-height: 1.3; font-family: inherit;
    }
    .fyr-tile .t-icon { font-size: 22px; display: block; margin-bottom: 7px; }
    .fyr-tile .t-lbl  { display: block; color: #555; font-size: 11.5px; line-height: 1.35; }
    .fyr-tile.on {
        border-color: #E63946;
        background: rgba(230,57,70,0.12);
    }
    .fyr-tile.on .t-lbl { color: #E63946; }

    /* Destination grid */
    .fyr-dest-grid {
        display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px;
    }
    .fyr-dest-btn {
        padding: 12px 6px 10px; border: 1.5px solid rgba(0,0,0,0.1);
        border-radius: 13px; background: #F5F5F5; font-size: 11px;
        cursor: pointer; text-align: center; transition: all 0.2s ease; font-family: inherit;
    }
    .fyr-dest-btn .d-icon { font-size: 20px; display: block; margin-bottom: 5px; }
    .fyr-dest-btn .d-lbl  { font-size: 11px; color: #555; }
    .fyr-dest-btn.on { border-color: #E63946; background: rgba(230,57,70,0.12); }
    .fyr-dest-btn.on .d-lbl { color: #E63946; }

    /* Pill chips (budget / timeline) */
    .fyr-pill-wrap { display: flex; flex-wrap: wrap; gap: 8px; }
    .fyr-pill {
        padding: 9px 14px; border: 1.5px solid rgba(0,0,0,0.1);
        border-radius: 100px; background: #F5F5F5; color: #555;
        font-size: 12px; cursor: pointer; white-space: nowrap;
        transition: all 0.2s ease; font-family: inherit;
    }
    .fyr-pill.on {
        border-color: #E63946;
        background: rgba(230,57,70,0.12);
        color: #E63946; font-weight: 600;
    }

    /* Text inputs */
    .fyr-field-label {
        display: block; font-size: 10px; letter-spacing: 0.14em;
        text-transform: uppercase; color: #E63946; font-weight: 500; margin-bottom: 8px;
    }
    .fyr-input {
        width: 100%; padding: 13px 16px;
        background: #fff; border: 1px solid rgba(230,57,70,0.2);
        border-radius: 13px; color: #333; font-size: 15px;
        outline: none; transition: border-color 0.22s; font-family: inherit; box-sizing: border-box;
    }
    .fyr-input:focus { border-color: #E63946; }

    /* Country code + phone row */
    .fyr-phone-row { display: flex; gap: 8px; }
    .fyr-phone-row select {
        flex: 0 0 auto; min-width: 170px;
        padding: 13px 10px; background: #fff;
        border: 1px solid rgba(230,57,70,0.2); border-radius: 13px;
        color: #333; font-size: 13px; outline: none; cursor: pointer; font-family: inherit;
    }
    .fyr-phone-row select:focus { border-color: #E63946; }
    .fyr-phone-row .fyr-input { flex: 1; }

    .fyr-wa-label {
        display: flex; align-items: center; gap: 8px;
        margin-top: 10px; cursor: pointer; color: #666; font-size: 12px;
    }
    .fyr-wa-label input { width: 14px; height: 14px; accent-color: #E63946; cursor: pointer; }

    /* Errors */
    .fyr-err { color: #E63946; font-size: 11px; margin-top: 6px; min-height: 14px; }

    /* Nav buttons */
    .fyr-nav { display: flex; gap: 10px; margin-top: 24px; }
    .fyr-btn-back {
        flex: 0 0 48px; background: transparent;
        border: 1.5px solid rgba(0,0,0,0.1); color: #666;
        font-size: 18px; padding: 14px; border-radius: 13px;
        cursor: pointer; transition: all 0.22s ease; display: flex;
        align-items: center; justify-content: center;
    }
    .fyr-btn-back:hover { border-color: #E63946; color: #E63946; }
    .fyr-btn-cta {
        flex: 1; padding: 15px 18px; border-radius: 13px;
        background: linear-gradient(135deg, #E63946 0%, #C41E3A 100%);
        color: #fff; font-weight: 600; letter-spacing: 0.015em;
        border: none; font-size: 15px; cursor: pointer;
        transition: all 0.22s ease; font-family: inherit;
        box-shadow: 0 6px 22px rgba(230,57,70,0.32);
    }
    .fyr-btn-cta:hover { transform: translateY(-1px); box-shadow: 0 10px 28px rgba(230,57,70,0.4); }
    .fyr-btn-cta:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }

    /* Footer trust badges */
    .fyr-badges {
        display: flex; justify-content: center; gap: 16px;
        margin-top: 18px; flex-wrap: wrap;
    }
    .fyr-badge { display: flex; align-items: center; gap: 5px; font-size: 10.5px; color: #777; }

    /* Step blocks */
    .fyr-step { display: none; }
    .fyr-step.active { display: block; }
    .fyr-field-group { margin-bottom: 24px; }
    .fyr-field-group:last-child { margin-bottom: 0; }

    /* Success state */
    .fyr-success {
        padding: 52px 32px; text-align: center;
    }
    .fyr-success-icon {
        width: 72px; height: 72px; border-radius: 50%;
        border: 1.5px solid rgba(230,57,70,0.2);
        background: rgba(230,57,70,0.08);
        display: flex; align-items: center; justify-content: center;
        font-size: 32px; margin: 0 auto 20px;
        animation: fyr-pop 0.5s cubic-bezier(0.34,1.56,0.64,1) both;
    }
    .fyr-success h3 {
        font-family: 'Playfair Display', serif;
        font-size: 26px; color: #1A1A1A; margin-bottom: 10px; font-weight: 700;
    }
    .fyr-success h3 em { color: #E63946; font-style: italic; }
    .fyr-success p { font-size: 13.5px; color: #666; line-height: 1.75; max-width: 360px; margin: 0 auto 24px; }
    .fyr-success-chips { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; }
    .fyr-success-chip {
        padding: 8px 16px; border: 1px solid rgba(230,57,70,0.2);
        border-radius: 100px; font-size: 12px; color: #555;
        background: rgba(230,57,70,0.07);
    }

    /* Why section below the card */
    .fyr-why { max-width: 740px; margin: 0 auto 72px; padding: 0 16px; text-align: center; }
    .fyr-why h2 {
        font-family: 'Playfair Display', serif;
        font-size: 28px; color: #1A1A1A; margin-bottom: 8px;
    }
    .fyr-why > p { font-size: 14px; color: #777; margin-bottom: 32px; }
    .fyr-why-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; }
    .fyr-why-card {
        background: #fff; border: 1px solid rgba(0,0,0,0.07);
        border-radius: 20px; padding: 24px 20px; text-align: left;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    }
    .fyr-why-card .w-icon { font-size: 26px; margin-bottom: 12px; }
    .fyr-why-card h4 { font-size: 14px; font-weight: 600; color: #1A1A1A; margin-bottom: 6px; }
    .fyr-why-card p { font-size: 12.5px; color: #777; line-height: 1.6; margin: 0; }

    @keyframes fyr-pop {
        0%   { transform: scale(0.5); opacity: 0; }
        100% { transform: scale(1);   opacity: 1; }
    }
    @keyframes fyr-rise {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0);    }
    }
    .fyr-step.active { animation: fyr-rise 0.32s cubic-bezier(0.22,1,0.36,1) both; }

    @media (max-width: 560px) {
        .fyr-form-header, .fyr-form-body { padding-left: 20px; padding-right: 20px; }
        .fyr-tile-grid  { grid-template-columns: repeat(2, 1fr); }
        .fyr-dest-grid  { grid-template-columns: repeat(2, 1fr); }
        .fyr-phone-row  { flex-direction: column; }
        .fyr-phone-row select { min-width: unset; width: 100%; }
    }
</style>
@endsection

@section('content')
<div class="fyr-page">

    {{-- ── Hero ─────────────────────────────────────────────── --}}
    <div class="fyr-hero">
        <div class="fyr-hero-badge">🌿 Personalised Retreat Finder</div>
        <h1>Find Your <em>Perfect</em> Retreat</h1>
        <p>Answer 3 quick questions and our wellness experts will hand-pick the ideal retreat experience just for you — completely free.</p>
        <div class="fyr-trust-row">
            <div class="fyr-trust-chip">🔒 100% Private</div>
            <div class="fyr-trust-chip">✅ Completely Free</div>
            <div class="fyr-trust-chip">⚡ Response in Few Hours</div>
            <div class="fyr-trust-chip">🌿 Expert Curated</div>
        </div>
    </div>

    {{-- ── Form Card ─────────────────────────────────────────── --}}
    <div class="fyr-card-wrap">
        <div class="fyr-card" id="fyrCard">

            {{-- Header --}}
            <div class="fyr-form-header">
                <h2>Find Your <em>Perfect</em> Retreat</h2>
                <p>3 quick questions &nbsp;·&nbsp; 100% free &nbsp;·&nbsp; No commitment</p>
                <div class="fyr-pips">
                    <div class="fyr-pip active" id="fyrP1"></div>
                    <div class="fyr-pip" id="fyrP2"></div>
                    <div class="fyr-pip" id="fyrP3"></div>
                </div>
                <div class="fyr-step-lbl" id="fyrStepLbl">Step 1 of 3</div>
            </div>

            {{-- Form Body --}}
            <div class="fyr-form-body">
                <form id="fyrForm" method="POST" action="{{ route('save.quick-lead') }}">
                    @csrf

                    {{-- ── STEP 1: Retreat Type + Destination ──────── --}}
                    <div class="fyr-step active" id="fyrS1">

                        <div class="fyr-field-group">
                            <div class="fyr-section-label">What are you looking for?</div>
                            <div class="fyr-tile-grid" id="fyrGRet">
                                <button type="button" class="fyr-tile" data-v="yoga_retreat">
                                    <span class="t-icon">🧘</span>
                                    <span class="t-lbl">Yoga Retreat</span>
                                </button>
                                <button type="button" class="fyr-tile" data-v="detox">
                                    <span class="t-icon">🥗</span>
                                    <span class="t-lbl">Detox Program</span>
                                </button>
                                <button type="button" class="fyr-tile" data-v="rejuvenation_meditation">
                                    <span class="t-icon">🕉️</span>
                                    <span class="t-lbl">Rejuvenation &amp; Meditation</span>
                                </button>
                                <button type="button" class="fyr-tile" data-v="weight_loss">
                                    <span class="t-icon">⚖️</span>
                                    <span class="t-lbl">Weight Loss</span>
                                </button>
                                <button type="button" class="fyr-tile" data-v="panchakarma">
                                    <span class="t-icon">🌺</span>
                                    <span class="t-lbl">Panchakarma</span>
                                </button>
                                <button type="button" class="fyr-tile" data-v="other_ayurvedic">
                                    <span class="t-icon">🌿</span>
                                    <span class="t-lbl">Other Ayurvedic</span>
                                </button>
                            </div>
                            <div class="fyr-err" id="fyrERet"></div>
                        </div>

                        <div class="fyr-field-group">
                            <div class="fyr-section-label">Preferred destination</div>
                            <div class="fyr-dest-grid" id="fyrGDest">
                                <button type="button" class="fyr-dest-btn" data-v="india">
                                    <span class="d-icon">🇮🇳</span>
                                    <span class="d-lbl">India</span>
                                </button>
                                <button type="button" class="fyr-dest-btn" data-v="thailand">
                                    <span class="d-icon">🇹🇭</span>
                                    <span class="d-lbl">Thailand</span>
                                </button>
                                <button type="button" class="fyr-dest-btn" data-v="indonesia">
                                    <span class="d-icon">🇮🇩</span>
                                    <span class="d-lbl">Indonesia</span>
                                </button>
                                <button type="button" class="fyr-dest-btn" data-v="open">
                                    <span class="d-icon">🌏</span>
                                    <span class="d-lbl">Open to all</span>
                                </button>
                            </div>
                            <div class="fyr-err" id="fyrEDest"></div>
                        </div>
                    </div>

                    {{-- ── STEP 2: Budget + Timeline ─────────────────── --}}
                    <div class="fyr-step" id="fyrS2">

                        <div class="fyr-field-group">
                            <div class="fyr-section-label">Budget per day</div>
                            <div class="fyr-pill-wrap" id="fyrGBudget">
                                <button type="button" class="fyr-pill" data-v="under_3k">Under ₹3,000</button>
                                <button type="button" class="fyr-pill" data-v="3k_5k">₹3,000 – 5,000</button>
                                <button type="button" class="fyr-pill" data-v="5k_8k">₹5,000 – 8,000</button>
                                <button type="button" class="fyr-pill" data-v="8k_12k">₹8,000 – 12,000</button>
                                <button type="button" class="fyr-pill" data-v="12k_18k">₹12,000 – 18,000</button>
                                <button type="button" class="fyr-pill" data-v="18k_25k">₹18,000 – 25,000 ⭐</button>
                                <button type="button" class="fyr-pill" data-v="25k_40k">₹25,000 – 40,000</button>
                                <button type="button" class="fyr-pill" data-v="40k_60k">₹40,000 – 60,000</button>
                                <button type="button" class="fyr-pill" data-v="60k_100k">₹60,000 – 1,00,000</button>
                                <button type="button" class="fyr-pill" data-v="above_100k">Above ₹1,00,000 👑</button>
                                <button type="button" class="fyr-pill" data-v="flexible">Flexible / Not sure</button>
                            </div>
                            <div class="fyr-err" id="fyrEBudget"></div>
                        </div>

                        <div class="fyr-field-group">
                            <div class="fyr-section-label">When are you planning to travel?</div>
                            <div class="fyr-pill-wrap" id="fyrGTime">
                                <button type="button" class="fyr-pill" data-v="within_2w">Within 2 weeks 🔥</button>
                                <button type="button" class="fyr-pill" data-v="next_month">Next month</button>
                                <button type="button" class="fyr-pill" data-v="1_3m">1–3 months</button>
                                <button type="button" class="fyr-pill" data-v="3_6m">3–6 months</button>
                                <button type="button" class="fyr-pill" data-v="exploring">Just exploring</button>
                            </div>
                            <div class="fyr-err" id="fyrETime"></div>
                        </div>
                    </div>

                    {{-- ── STEP 3: Contact Details ───────────────────── --}}
                    <div class="fyr-step" id="fyrS3">

                        <div class="fyr-field-group">
                            <label class="fyr-field-label">Your Name *</label>
                            <input type="text" id="fyrName" name="name" class="fyr-input"
                                   placeholder="Full name" autocomplete="name">
                            <div class="fyr-err" id="fyrEName"></div>
                        </div>

                        <div class="fyr-field-group">
                            <label class="fyr-field-label">Phone Number *</label>
                            <div class="fyr-phone-row">
                                <select id="fyrCountryCode" name="country_code">
                                    <option value="91" selected>🇮🇳 India +91</option>
                                    <option value="1">🇺🇸 USA +1</option>
                                    <option value="44">🇬🇧 UK +44</option>
                                    <option value="61">🇦🇺 Australia +61</option>
                                    <option value="971">🇦🇪 UAE +971</option>
                                    <option value="65">🇸🇬 Singapore +65</option>
                                    <option value="60">🇲🇾 Malaysia +60</option>
                                    <option value="66">🇹🇭 Thailand +66</option>
                                    <option value="62">🇮🇩 Indonesia +62</option>
                                    <option value="94">🇱🇰 Sri Lanka +94</option>
                                    <option value="977">🇳🇵 Nepal +977</option>
                                    <option value="880">🇧🇩 Bangladesh +880</option>
                                    <option value="49">🇩🇪 Germany +49</option>
                                    <option value="33">🇫🇷 France +33</option>
                                    <option value="39">🇮🇹 Italy +39</option>
                                    <option value="34">🇪🇸 Spain +34</option>
                                    <option value="31">🇳🇱 Netherlands +31</option>
                                    <option value="41">🇨🇭 Switzerland +41</option>
                                    <option value="43">🇦🇹 Austria +43</option>
                                    <option value="32">🇧🇪 Belgium +32</option>
                                    <option value="46">🇸🇪 Sweden +46</option>
                                    <option value="47">🇳🇴 Norway +47</option>
                                    <option value="45">🇩🇰 Denmark +45</option>
                                    <option value="358">🇫🇮 Finland +358</option>
                                    <option value="7">🇷🇺 Russia +7</option>
                                    <option value="81">🇯🇵 Japan +81</option>
                                    <option value="82">🇰🇷 Korea South +82</option>
                                    <option value="86">🇨🇳 China +86</option>
                                    <option value="852">🇭🇰 Hong Kong +852</option>
                                    <option value="886">🇹🇼 Taiwan +886</option>
                                    <option value="63">🇵🇭 Philippines +63</option>
                                    <option value="84">🇻🇳 Vietnam +84</option>
                                    <option value="95">🇲🇲 Myanmar +95</option>
                                    <option value="855">🇰🇭 Cambodia +855</option>
                                    <option value="856">🇱🇦 Laos +856</option>
                                    <option value="673">🇧🇳 Brunei +673</option>
                                    <option value="960">🇲🇻 Maldives +960</option>
                                    <option value="966">🇸🇦 Saudi Arabia +966</option>
                                    <option value="974">🇶🇦 Qatar +974</option>
                                    <option value="965">🇰🇼 Kuwait +965</option>
                                    <option value="968">🇴🇲 Oman +968</option>
                                    <option value="973">🇧🇭 Bahrain +973</option>
                                    <option value="962">🇯🇴 Jordan +962</option>
                                    <option value="961">🇱🇧 Lebanon +961</option>
                                    <option value="20">🇪🇬 Egypt +20</option>
                                    <option value="27">🇿🇦 South Africa +27</option>
                                    <option value="254">🇰🇪 Kenya +254</option>
                                    <option value="234">🇳🇬 Nigeria +234</option>
                                    <option value="55">🇧🇷 Brazil +55</option>
                                    <option value="52">🇲🇽 Mexico +52</option>
                                    <option value="54">🇦🇷 Argentina +54</option>
                                    <option value="57">🇨🇴 Colombia +57</option>
                                    <option value="51">🇵🇪 Peru +51</option>
                                    <option value="64">🇳🇿 New Zealand +64</option>
                                </select>
                                <input type="tel" id="fyrPhone" name="phone" class="fyr-input"
                                       placeholder="Phone number" maxlength="15"
                                       inputmode="numeric" autocomplete="tel">
                            </div>
                            <label class="fyr-wa-label">
                                <input type="checkbox" id="fyrWA"> This number is on WhatsApp
                            </label>
                            <div class="fyr-err" id="fyrEPhone"></div>
                        </div>

                        <div class="fyr-field-group">
                            <label class="fyr-field-label">Email Address *</label>
                            <input type="email" id="fyrEmail" name="email" class="fyr-input"
                                   placeholder="you@email.com" autocomplete="email">
                            <div class="fyr-err" id="fyrEEmail"></div>
                        </div>
                    </div>

                    {{-- Hidden fields --}}
                    <input type="hidden" name="ref_url"      value="{{ url()->current() }}" />
                    <input type="hidden" id="fyrHRetreatType" name="retreat_type" />
                    <input type="hidden" id="fyrHDestination" name="destination" />
                    <input type="hidden" id="fyrHBudget"      name="budget" />
                    <input type="hidden" id="fyrHTimeline"    name="timeline" />
                    <input type="hidden" id="fyrHWhatsapp"    name="whatsapp" value="0" />

                    {{-- Nav --}}
                    <div class="fyr-nav">
                        <button type="button" class="fyr-btn-back" id="fyrBackBtn"
                                style="display:none;" onclick="fyrBack()">←</button>
                        <button type="button" class="fyr-btn-cta" id="fyrNextBtn"
                                onclick="fyrNext()">Continue →</button>
                    </div>

                    <div class="fyr-badges">
                        <div class="fyr-badge">🔒 100% private</div>
                        <div class="fyr-badge">✅ Completely free</div>
                        <div class="fyr-badge">⚡ Response in 2 hrs</div>
                        <div class="fyr-badge">🌿 Expert curated</div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Why BalanceBoat Section ──────────────────────────── --}}
    <div class="fyr-why">
        <h2>Why Let Us Find Your Retreat?</h2>
        <p>We've helped thousands of travellers find the perfect wellness experience</p>
        <div class="fyr-why-grid">
            <div class="fyr-why-card">
                <div class="w-icon">🔍</div>
                <h4>Expert Curation</h4>
                <p>We personally vet every retreat centre on our platform for quality and authenticity.</p>
            </div>
            <div class="fyr-why-card">
                <div class="w-icon">⚡</div>
                <h4>Few Hours Response</h4>
                <p>Our wellness planners reach out with personalised picks within few hours of your inquiry.</p>
            </div>
            <div class="fyr-why-card">
                <div class="w-icon">💸</div>
                <h4>Best Price Promise</h4>
                <p>We negotiate directly with retreat centres to secure the best available rates for you.</p>
            </div>
            <div class="fyr-why-card">
                <div class="w-icon">🛡️</div>
                <h4>Zero Commitment</h4>
                <p>Explore options with zero pressure. Book only when you find exactly what you're looking for.</p>
            </div>
        </div>
    </div>

</div>

<script>
(function () {
    'use strict';

    let fyrCur = 1;
    const fyr = {
        retreatType: '', destination: '',
        budget: '',     timeline: '',
        name: '',       phone: '',
        countryCode: '91',
        whatsapp: false, email: ''
    };

    // ── Selection binders ──────────────────────────────────
    function fyrBind(gridId, key, errId, btnClass) {
        document.querySelectorAll('#' + gridId + ' button[data-v]').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('#' + gridId + ' button[data-v]').forEach(function(b) {
                    b.classList.remove('on');
                });
                btn.classList.add('on');
                fyr[key] = btn.dataset.v;
                if (errId) document.getElementById(errId).textContent = '';
            });
        });
    }

    fyrBind('fyrGRet',    'retreatType', 'fyrERet');
    fyrBind('fyrGDest',   'destination', 'fyrEDest');
    fyrBind('fyrGBudget', 'budget',      'fyrEBudget');
    fyrBind('fyrGTime',   'timeline',    'fyrETime');

    // ── Field listeners ────────────────────────────────────
    document.getElementById('fyrCountryCode').addEventListener('change', function(e) {
        fyr.countryCode = e.target.value;
        document.getElementById('fyrEPhone').textContent = '';
    });

    document.getElementById('fyrName').addEventListener('input', function(e) {
        fyr.name = e.target.value.trim();
        document.getElementById('fyrEName').textContent = '';
    });

    document.getElementById('fyrPhone').addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '').slice(0, 15);
        fyr.phone = e.target.value;
        document.getElementById('fyrEPhone').textContent = '';
    });

    document.getElementById('fyrEmail').addEventListener('input', function(e) {
        fyr.email = e.target.value.trim();
        document.getElementById('fyrEEmail').textContent = '';
    });

    document.getElementById('fyrWA').addEventListener('change', function(e) {
        fyr.whatsapp = e.target.checked;
        document.getElementById('fyrHWhatsapp').value = e.target.checked ? '1' : '0';
    });

    // ── Validation ─────────────────────────────────────────
    function fyrValidate() {
        let ok = true;
        if (fyrCur === 1) {
            if (!fyr.retreatType) { document.getElementById('fyrERet').textContent  = 'Please choose a retreat type'; ok = false; }
            if (!fyr.destination) { document.getElementById('fyrEDest').textContent = 'Please pick a destination';   ok = false; }
        }
        if (fyrCur === 2) {
            if (!fyr.budget)   { document.getElementById('fyrEBudget').textContent = 'Please select a budget range'; ok = false; }
            if (!fyr.timeline) { document.getElementById('fyrETime').textContent   = 'Please select a travel window'; ok = false; }
        }
        if (fyrCur === 3) {
            if (!fyr.name || fyr.name.length < 2)
                { document.getElementById('fyrEName').textContent  = 'Please enter your full name'; ok = false; }
            if (!fyr.phone || fyr.phone.length < 6)
                { document.getElementById('fyrEPhone').textContent = 'Enter a valid phone number'; ok = false; }
            if (!fyr.email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(fyr.email))
                { document.getElementById('fyrEEmail').textContent = 'Enter a valid email address'; ok = false; }
        }
        return ok;
    }

    // ── UI refresh ─────────────────────────────────────────
    function fyrRefresh() {
        document.querySelectorAll('.fyr-step').forEach(function(s) { s.classList.remove('active'); });
        document.getElementById('fyrS' + fyrCur).classList.add('active');

        for (var i = 1; i <= 3; i++) {
            var pip = document.getElementById('fyrP' + i);
            if (i <= fyrCur) { pip.classList.add('active'); }
            else              { pip.classList.remove('active'); }
        }

        document.getElementById('fyrStepLbl').textContent = 'Step ' + fyrCur + ' of 3';

        var backBtn = document.getElementById('fyrBackBtn');
        backBtn.style.display = fyrCur > 1 ? 'flex' : 'none';

        document.getElementById('fyrNextBtn').textContent =
            fyrCur === 3 ? '✨  Get My Retreat Picks' : 'Continue →';

        // Scroll card top into view on mobile
        document.getElementById('fyrCard').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    // ── Navigation ─────────────────────────────────────────
    window.fyrNext = function() {
        if (!fyrValidate()) return;
        if (fyrCur < 3) { fyrCur++; fyrRefresh(); return; }
        fyrSubmit();
    };

    window.fyrBack = function() {
        if (fyrCur > 1) { fyrCur--; fyrRefresh(); }
    };

    // ── Submit ─────────────────────────────────────────────
    async function fyrSubmit() {
        document.getElementById('fyrHRetreatType').value = fyr.retreatType;
        document.getElementById('fyrHDestination').value = fyr.destination;
        document.getElementById('fyrHBudget').value      = fyr.budget;
        document.getElementById('fyrHTimeline').value    = fyr.timeline;

        var btn = document.getElementById('fyrNextBtn');
        btn.disabled = true;
        btn.textContent = '⏳  Sending…';

        try {
            var form = document.getElementById('fyrForm');
            var formData = new FormData(form);
            formData.set('country_code', fyr.countryCode);

            var res = await fetch(form.action, { method: 'POST', body: formData });
            if (!res.ok) throw new Error('HTTP ' + res.status);
        } catch (err) {
            console.error('Quick lead error:', err);
        }

        fyrShowSuccess();
        btn.disabled = false;
    }

    // ── Success state ──────────────────────────────────────
    function fyrShowSuccess() {
        var card = document.getElementById('fyrCard');
        card.innerHTML =
            '<div class="fyr-success">' +
            '<div class="fyr-success-icon">🌿</div>' +
            '<h3>You\'re all set, <em>' + (fyr.name || 'friend') + '</em>!</h3>' +
            '<p>Our wellness planners are hand-picking retreats that match your preferences. ' +
            'Expect a personalised call or WhatsApp message within 2 hours.</p>' +
            '<div class="fyr-success-chips">' +
            '<div class="fyr-success-chip">📞 Call within 2 hrs</div>' +
            '<div class="fyr-success-chip">📱 WhatsApp update</div>' +
            '<div class="fyr-success-chip">✨ Curated just for you</div>' +
            '</div></div>';
    }

    // ── Init ───────────────────────────────────────────────
    fyrRefresh();
}());
</script>
@endsection
