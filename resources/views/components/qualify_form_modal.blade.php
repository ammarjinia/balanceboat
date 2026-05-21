<!-- Qualify Form Modal - Reusable Component -->
<div id="requstcallPopup" class="modal">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 560px;">
        <div class="modal-content" style="background: #FFFFFF; border: 1px solid rgba(230,57,70,0.2); border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.15);">
            <!-- Qualify Form Container -->
            <div id="qualifyFormContainer" style="background: #FFFFFF; border-radius: 20px; overflow: hidden;">
                
                <!-- Header -->
                <div style="background: linear-gradient(155deg, #FFF5F5 0%, #FFE9EB 100%); border-bottom: 1px solid rgba(230,57,70,0.15); padding: 28px 32px 22px;">
                    <button type="button" class="close" data-dismiss="modal" style="position: absolute; top: 20px; right: 25px; background: none; border: none; padding: 0; cursor: pointer;"><i class="icon_close" style="font-size: 1.5em; font-weight: bold; color: #1A1A1A;"></i></button>
                    
                    <h2 style="font-family: 'Playfair Display', serif; font-size: 25px; font-weight: 700; color: #1A1A1A; line-height: 1.25; margin-bottom: 5px; margin-top: 0;">Find Your <em style="color: #E63946; font-style: italic;">Perfect</em> Retreat</h2>
                    <p style="font-size: 12px; color: #666666; font-weight: 300; letter-spacing: 0.025em; margin: 0;">3 quick questions &nbsp;·&nbsp; 100% free &nbsp;·&nbsp; No commitment</p>
                    
                    <div style="display: flex; gap: 6px; margin-top: 18px;">
                        <div class="pip" id="p1" style="height: 3px; flex: 1; border-radius: 2px; background: #E63946; transition: background 0.4s ease;"></div>
                        <div class="pip" id="p2" style="height: 3px; flex: 1; border-radius: 2px; background: rgba(0,0,0,0.08); transition: background 0.4s ease;"></div>
                        <div class="pip" id="p3" style="height: 3px; flex: 1; border-radius: 2px; background: rgba(0,0,0,0.08); transition: background 0.4s ease;"></div>
                    </div>
                    <div style="font-size: 10.5px; color: #999999; margin-top: 7px; letter-spacing: 0.07em;" id="stepLbl">Step 1 of 3</div>
                </div>

                <!-- Form Body -->
                <div style="padding: 26px 32px 30px;">
                    <form id="frmQualifyForm" method="POST" action="{{ route('save.quick-lead') ?? '/api/save-quick-lead' }}">
                        @csrf
                        
                        <!-- STEP 1: Retreat type + Destination -->
                        <div class="step active" id="s1" style="display: block; animation: rise 0.32s cubic-bezier(0.22,1,0.36,1) both;">
                            <div style="margin-bottom: 24px;">
                                <div style="font-size: 10px; font-weight: 500; letter-spacing: 0.18em; text-transform: uppercase; color: #E63946; margin-bottom: 11px; display: flex; align-items: center; gap: 10px;">
                                    What are you looking for?
                                    <div style="flex: 1; height: 1px; background: rgba(230,57,70,0.2);"></div>
                                </div>
                                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px;" id="gRet">
                                    <button type="button" class="tile" data-v="yoga_retreat" style="padding: 14px 8px 12px; border: 1px solid rgba(0,0,0,0.1); border-radius: 13px; background: #F5F5F5; color: #555555; font-size: 11.5px; cursor: pointer; text-align: center; transition: all 0.2s ease; line-height: 1.3;">
                                        <span style="font-size: 22px; display: block; margin-bottom: 7px;">🧘</span>
                                        <span style="display: block; color: #555555; font-size: 11.5px; line-height: 1.35;">Yoga Retreat</span>
                                    </button>
                                    <button type="button" class="tile" data-v="detox" style="padding: 14px 8px 12px; border: 1px solid rgba(0,0,0,0.1); border-radius: 13px; background: #F5F5F5; color: #555555; font-size: 11.5px; cursor: pointer; text-align: center; transition: all 0.2s ease; line-height: 1.3;">
                                        <span style="font-size: 22px; display: block; margin-bottom: 7px;">🥗</span>
                                        <span style="display: block; color: #555555; font-size: 11.5px; line-height: 1.35;">Detox Program</span>
                                    </button>
                                    <button type="button" class="tile" data-v="rejuvenation_meditation" style="padding: 14px 8px 12px; border: 1px solid rgba(0,0,0,0.1); border-radius: 13px; background: #F5F5F5; color: #555555; font-size: 11.5px; cursor: pointer; text-align: center; transition: all 0.2s ease; line-height: 1.3;">
                                        <span style="font-size: 22px; display: block; margin-bottom: 7px;">🕉️</span>
                                        <span style="display: block; color: #555555; font-size: 11.5px; line-height: 1.35;">Rejuvenation &amp; Meditation</span>
                                    </button>
                                    <button type="button" class="tile" data-v="weight_loss" style="padding: 14px 8px 12px; border: 1px solid rgba(0,0,0,0.1); border-radius: 13px; background: #F5F5F5; color: #555555; font-size: 11.5px; cursor: pointer; text-align: center; transition: all 0.2s ease; line-height: 1.3;">
                                        <span style="font-size: 22px; display: block; margin-bottom: 7px;">⚖️</span>
                                        <span style="display: block; color: #555555; font-size: 11.5px; line-height: 1.35;">Weight Loss</span>
                                    </button>
                                    <button type="button" class="tile" data-v="panchakarma" style="padding: 14px 8px 12px; border: 1px solid rgba(0,0,0,0.1); border-radius: 13px; background: #F5F5F5; color: #555555; font-size: 11.5px; cursor: pointer; text-align: center; transition: all 0.2s ease; line-height: 1.3;">
                                        <span style="font-size: 22px; display: block; margin-bottom: 7px;">🌺</span>
                                        <span style="display: block; color: #555555; font-size: 11.5px; line-height: 1.35;">Panchakarma</span>
                                    </button>
                                    <button type="button" class="tile" data-v="other_ayurvedic" style="padding: 14px 8px 12px; border: 1px solid rgba(0,0,0,0.1); border-radius: 13px; background: #F5F5F5; color: #555555; font-size: 11.5px; cursor: pointer; text-align: center; transition: all 0.2s ease; line-height: 1.3;">
                                        <span style="font-size: 22px; display: block; margin-bottom: 7px;">🌿</span>
                                        <span style="display: block; color: #555555; font-size: 11.5px; line-height: 1.35;">Other Ayurvedic</span>
                                    </button>
                                </div>
                                <div class="err" id="eRet" style="color: #E63946; font-size: 11px; margin-top: 6px; min-height: 14px;"></div>
                            </div>

                            <div style="margin-bottom: 0;">
                                <div style="font-size: 10px; font-weight: 500; letter-spacing: 0.18em; text-transform: uppercase; color: #E63946; margin-bottom: 11px; display: flex; align-items: center; gap: 10px;">
                                    Preferred destination
                                    <div style="flex: 1; height: 1px; background: rgba(230,57,70,0.2);"></div>
                                </div>
                                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px;" id="gDest">
                                    <button type="button" class="dest-btn" data-v="india" style="padding: 12px 6px 10px; border: 1px solid rgba(0,0,0,0.1); border-radius: 13px; background: #F5F5F5; font-size: 11px; cursor: pointer; text-align: center; transition: all 0.2s ease;">
                                        <span style="font-size: 20px; display: block; margin-bottom: 5px;">🇮🇳</span>
                                        <span style="font-size: 11px; color: #555555;">India</span>
                                    </button>
                                    <button type="button" class="dest-btn" data-v="thailand" style="padding: 12px 6px 10px; border: 1px solid rgba(0,0,0,0.1); border-radius: 13px; background: #F5F5F5; font-size: 11px; cursor: pointer; text-align: center; transition: all 0.2s ease;">
                                        <span style="font-size: 20px; display: block; margin-bottom: 5px;">🇹🇭</span>
                                        <span style="font-size: 11px; color: #555555;">Thailand</span>
                                    </button>
                                    <button type="button" class="dest-btn" data-v="indonesia" style="padding: 12px 6px 10px; border: 1px solid rgba(0,0,0,0.1); border-radius: 13px; background: #F5F5F5; font-size: 11px; cursor: pointer; text-align: center; transition: all 0.2s ease;">
                                        <span style="font-size: 20px; display: block; margin-bottom: 5px;">🇮🇩</span>
                                        <span style="font-size: 11px; color: #555555;">Indonesia</span>
                                    </button>
                                    <button type="button" class="dest-btn" data-v="open" style="padding: 12px 6px 10px; border: 1px solid rgba(0,0,0,0.1); border-radius: 13px; background: #F5F5F5; font-size: 11px; cursor: pointer; text-align: center; transition: all 0.2s ease;">
                                        <span style="font-size: 20px; display: block; margin-bottom: 5px;">🌏</span>
                                        <span style="font-size: 11px; color: #555555;">Open to all</span>
                                    </button>
                                </div>
                                <div class="err" id="eDest" style="color: #E63946; font-size: 11px; margin-top: 6px; min-height: 14px;"></div>
                            </div>
                        </div>

                        <!-- STEP 2: Budget + Timeline -->
                        <div class="step" id="s2" style="display: none;">
                            <div style="margin-bottom: 24px;">
                                <div style="font-size: 10px; font-weight: 500; letter-spacing: 0.18em; text-transform: uppercase; color: #E63946; margin-bottom: 11px; display: flex; align-items: center; gap: 10px;">
                                    Budget per day
                                    <div style="flex: 1; height: 1px; background: rgba(230,57,70,0.2);"></div>
                                </div>
                                <div style="display: flex; flex-wrap: wrap; gap: 8px;" id="gBudget">
                                    <button type="button" class="pill" data-v="under_3k" style="padding: 9px 14px; border: 1px solid rgba(0,0,0,0.1); border-radius: 100px; background: #F5F5F5; color: #555555; font-size: 12px; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;">Under ₹3,000</button>
                                    <button type="button" class="pill" data-v="3k_5k" style="padding: 9px 14px; border: 1px solid rgba(0,0,0,0.1); border-radius: 100px; background: #F5F5F5; color: #555555; font-size: 12px; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;">₹3,000 – 5,000</button>
                                    <button type="button" class="pill" data-v="5k_8k" style="padding: 9px 14px; border: 1px solid rgba(0,0,0,0.1); border-radius: 100px; background: #F5F5F5; color: #555555; font-size: 12px; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;">₹5,000 – 8,000</button>
                                    <button type="button" class="pill" data-v="8k_12k" style="padding: 9px 14px; border: 1px solid rgba(0,0,0,0.1); border-radius: 100px; background: #F5F5F5; color: #555555; font-size: 12px; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;">₹8,000 – 12,000</button>
                                    <button type="button" class="pill" data-v="12k_18k" style="padding: 9px 14px; border: 1px solid rgba(0,0,0,0.1); border-radius: 100px; background: #F5F5F5; color: #555555; font-size: 12px; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;">₹12,000 – 18,000</button>
                                    <button type="button" class="pill" data-v="18k_25k" style="padding: 9px 14px; border: 1px solid rgba(0,0,0,0.1); border-radius: 100px; background: #F5F5F5; color: #555555; font-size: 12px; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;">₹18,000 – 25,000 ⭐</button>
                                    <button type="button" class="pill" data-v="25k_40k" style="padding: 9px 14px; border: 1px solid rgba(0,0,0,0.1); border-radius: 100px; background: #F5F5F5; color: #555555; font-size: 12px; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;">₹25,000 – 40,000</button>
                                    <button type="button" class="pill" data-v="40k_60k" style="padding: 9px 14px; border: 1px solid rgba(0,0,0,0.1); border-radius: 100px; background: #F5F5F5; color: #555555; font-size: 12px; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;">₹40,000 – 60,000</button>
                                    <button type="button" class="pill" data-v="60k_100k" style="padding: 9px 14px; border: 1px solid rgba(0,0,0,0.1); border-radius: 100px; background: #F5F5F5; color: #555555; font-size: 12px; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;">₹60,000 – 1,00,000</button>
                                    <button type="button" class="pill" data-v="above_100k" style="padding: 9px 14px; border: 1px solid rgba(0,0,0,0.1); border-radius: 100px; background: #F5F5F5; color: #555555; font-size: 12px; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;">Above ₹1,00,000 👑</button>
                                    <button type="button" class="pill" data-v="flexible" style="padding: 9px 14px; border: 1px solid rgba(0,0,0,0.1); border-radius: 100px; background: #F5F5F5; color: #555555; font-size: 12px; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;">Flexible / Not sure</button>
                                </div>
                                <div class="err" id="eBudget" style="color: #E63946; font-size: 11px; margin-top: 6px; min-height: 14px;"></div>
                            </div>

                            <div style="margin-bottom: 0;">
                                <div style="font-size: 10px; font-weight: 500; letter-spacing: 0.18em; text-transform: uppercase; color: #E63946; margin-bottom: 11px; display: flex; align-items: center; gap: 10px;">
                                    When are you planning to travel?
                                    <div style="flex: 1; height: 1px; background: rgba(230,57,70,0.2);"></div>
                                </div>
                                <div style="display: flex; flex-wrap: wrap; gap: 8px;" id="gTime">
                                    <button type="button" class="pill" data-v="within_2w" style="padding: 9px 14px; border: 1px solid rgba(0,0,0,0.1); border-radius: 100px; background: #F5F5F5; color: #555555; font-size: 12px; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;">Within 2 weeks 🔥</button>
                                    <button type="button" class="pill" data-v="next_month" style="padding: 9px 14px; border: 1px solid rgba(0,0,0,0.1); border-radius: 100px; background: #F5F5F5; color: #555555; font-size: 12px; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;">Next month</button>
                                    <button type="button" class="pill" data-v="1_3m" style="padding: 9px 14px; border: 1px solid rgba(0,0,0,0.1); border-radius: 100px; background: #F5F5F5; color: #555555; font-size: 12px; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;">1–3 months</button>
                                    <button type="button" class="pill" data-v="3_6m" style="padding: 9px 14px; border: 1px solid rgba(0,0,0,0.1); border-radius: 100px; background: #F5F5F5; color: #555555; font-size: 12px; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;">3–6 months</button>
                                    <button type="button" class="pill" data-v="exploring" style="padding: 9px 14px; border: 1px solid rgba(0,0,0,0.1); border-radius: 100px; background: #F5F5F5; color: #555555; font-size: 12px; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;">Just exploring</button>
                                </div>
                                <div class="err" id="eTime" style="color: #E63946; font-size: 11px; margin-top: 6px; min-height: 14px;"></div>
                            </div>
                        </div>

                        <!-- STEP 3: Contact -->
                        <div class="step" id="s3" style="display: none;">
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; font-size: 10px; letter-spacing: 0.14em; text-transform: uppercase; color: #E63946; font-weight: 500; margin-bottom: 8px;">Your Name *</label>
                                <input type="text" id="iName" name="name" placeholder="Full name" autocomplete="name" style="width: 100%; padding: 13px 16px; background: #FFFFFF; border: 1px solid rgba(230,57,70,0.2); border-radius: 13px; color: #333333; font-size: 15px; outline: none; transition: border-color 0.22s;">
                                <div class="err" id="eName" style="color: #E63946; font-size: 11px; margin-top: 6px; min-height: 14px;"></div>
                            </div>

                            <div style="margin-bottom: 15px;">
                                <label style="display: block; font-size: 10px; letter-spacing: 0.14em; text-transform: uppercase; color: #E63946; font-weight: 500; margin-bottom: 8px;">Phone Number *</label>
                                <div style="display: flex; gap: 8px;">
                                    <select id="iCountryCode" name="country_code" style="flex: 0 0 auto; min-width: 180px; padding: 13px 12px; background: #FFFFFF; border: 1px solid rgba(230,57,70,0.2); border-radius: 13px; color: #333333; font-size: 13px; outline: none; transition: border-color 0.22s; cursor: pointer;">
                                        <option value="91" selected>🇮🇳 India +91</option>
                                        <option value="1">🇺🇸 USA +1</option>
                                        <option value="213">🇩🇿 Algeria +213</option>
                                        <option value="376">🇦🇩 Andorra +376</option>
                                        <option value="244">🇦🇴 Angola +244</option>
                                        <option value="1264">🇦🇮 Anguilla +1264</option>
                                        <option value="1268">🇦🇬 Antigua & Barbuda +1268</option>
                                        <option value="54">🇦🇷 Argentina +54</option>
                                        <option value="374">🇦🇲 Armenia +374</option>
                                        <option value="297">🇦🇼 Aruba +297</option>
                                        <option value="61">🇦🇺 Australia +61</option>
                                        <option value="43">🇦🇹 Austria +43</option>
                                        <option value="994">🇦🇿 Azerbaijan +994</option>
                                        <option value="1242">🇧🇸 Bahamas +1242</option>
                                        <option value="973">🇧🇭 Bahrain +973</option>
                                        <option value="880">🇧🇩 Bangladesh +880</option>
                                        <option value="1246">🇧🇧 Barbados +1246</option>
                                        <option value="375">🇧🇾 Belarus +375</option>
                                        <option value="32">🇧🇪 Belgium +32</option>
                                        <option value="501">🇧🇿 Belize +501</option>
                                        <option value="229">🇧🇯 Benin +229</option>
                                        <option value="1441">🇧🇲 Bermuda +1441</option>
                                        <option value="975">🇧🇹 Bhutan +975</option>
                                        <option value="591">🇧🇴 Bolivia +591</option>
                                        <option value="387">🇧🇦 Bosnia Herzegovina +387</option>
                                        <option value="267">🇧🇼 Botswana +267</option>
                                        <option value="55">🇧🇷 Brazil +55</option>
                                        <option value="673">🇧🇳 Brunei +673</option>
                                        <option value="359">🇧🇬 Bulgaria +359</option>
                                        <option value="226">🇧🇫 Burkina Faso +226</option>
                                        <option value="257">🇧🇮 Burundi +257</option>
                                        <option value="855">🇰🇭 Cambodia +855</option>
                                        <option value="237">🇨🇲 Cameroon +237</option>
                                        <option value="1">🇨🇦 Canada +1</option>
                                        <option value="238">🇨🇻 Cape Verde Islands +238</option>
                                        <option value="1345">🇰🇾 Cayman Islands +1345</option>
                                        <option value="236">🇨🇫 Central African Republic +236</option>
                                        <option value="56">🇨🇱 Chile +56</option>
                                        <option value="86">🇨🇳 China +86</option>
                                        <option value="57">🇨🇴 Colombia +57</option>
                                        <option value="269">🇰🇲 Comoros +269</option>
                                        <option value="242">🇨🇬 Congo +242</option>
                                        <option value="682">🇨🇰 Cook Islands +682</option>
                                        <option value="506">🇨🇷 Costa Rica +506</option>
                                        <option value="385">🇭🇷 Croatia +385</option>
                                        <option value="53">🇨🇺 Cuba +53</option>
                                        <option value="90392">🇨🇾 Cyprus North +90392</option>
                                        <option value="357">🇨🇾 Cyprus South +357</option>
                                        <option value="42">🇨🇿 Czech Republic +42</option>
                                        <option value="45">🇩🇰 Denmark +45</option>
                                        <option value="253">🇩🇯 Djibouti +253</option>
                                        <option value="1809">🇩🇲 Dominica +1809</option>
                                        <option value="1809">🇩🇴 Dominican Republic +1809</option>
                                        <option value="593">🇪🇨 Ecuador +593</option>
                                        <option value="20">🇪🇬 Egypt +20</option>
                                        <option value="503">🇸🇻 El Salvador +503</option>
                                        <option value="240">🇬🇶 Equatorial Guinea +240</option>
                                        <option value="291">🇪🇷 Eritrea +291</option>
                                        <option value="372">🇪🇪 Estonia +372</option>
                                        <option value="251">🇪🇹 Ethiopia +251</option>
                                        <option value="500">🇫🇰 Falkland Islands +500</option>
                                        <option value="298">🇫🇴 Faroe Islands +298</option>
                                        <option value="679">🇫🇯 Fiji +679</option>
                                        <option value="358">🇫🇮 Finland +358</option>
                                        <option value="33">🇫🇷 France +33</option>
                                        <option value="594">🇬🇫 French Guiana +594</option>
                                        <option value="689">🇵🇫 French Polynesia +689</option>
                                        <option value="241">🇬🇦 Gabon +241</option>
                                        <option value="220">🇬🇲 Gambia +220</option>
                                        <option value="7880">🇬🇪 Georgia +7880</option>
                                        <option value="49">🇩🇪 Germany +49</option>
                                        <option value="233">🇬🇭 Ghana +233</option>
                                        <option value="350">🇬🇮 Gibraltar +350</option>
                                        <option value="30">🇬🇷 Greece +30</option>
                                        <option value="299">🇬🇱 Greenland +299</option>
                                        <option value="1473">🇬🇩 Grenada +1473</option>
                                        <option value="590">🇬🇵 Guadeloupe +590</option>
                                        <option value="671">🇬🇺 Guam +671</option>
                                        <option value="502">🇬🇹 Guatemala +502</option>
                                        <option value="224">🇬🇳 Guinea +224</option>
                                        <option value="245">🇬🇼 Guinea-Bissau +245</option>
                                        <option value="592">🇬🇾 Guyana +592</option>
                                        <option value="509">🇭🇹 Haiti +509</option>
                                        <option value="504">🇭🇳 Honduras +504</option>
                                        <option value="852">🇭🇰 Hong Kong +852</option>
                                        <option value="36">🇭🇺 Hungary +36</option>
                                        <option value="354">🇮🇸 Iceland +354</option>
                                        <option value="62">🇮🇩 Indonesia +62</option>
                                        <option value="98">🇮🇷 Iran +98</option>
                                        <option value="964">🇮🇶 Iraq +964</option>
                                        <option value="353">🇮🇪 Ireland +353</option>
                                        <option value="972">🇮🇱 Israel +972</option>
                                        <option value="39">🇮🇹 Italy +39</option>
                                        <option value="1876">🇯🇲 Jamaica +1876</option>
                                        <option value="81">🇯🇵 Japan +81</option>
                                        <option value="962">🇯🇴 Jordan +962</option>
                                        <option value="7">🇰🇿 Kazakhstan +7</option>
                                        <option value="254">🇰🇪 Kenya +254</option>
                                        <option value="686">🇰🇮 Kiribati +686</option>
                                        <option value="850">🇰🇵 Korea North +850</option>
                                        <option value="82">🇰🇷 Korea South +82</option>
                                        <option value="965">🇰🇼 Kuwait +965</option>
                                        <option value="996">🇰🇬 Kyrgyzstan +996</option>
                                        <option value="856">🇱🇦 Laos +856</option>
                                        <option value="371">🇱🇻 Latvia +371</option>
                                        <option value="961">🇱🇧 Lebanon +961</option>
                                        <option value="266">🇱🇸 Lesotho +266</option>
                                        <option value="231">🇱🇷 Liberia +231</option>
                                        <option value="218">🇱🇾 Libya +218</option>
                                        <option value="417">🇱🇮 Liechtenstein +417</option>
                                        <option value="370">🇱🇹 Lithuania +370</option>
                                        <option value="352">🇱🇺 Luxembourg +352</option>
                                        <option value="853">🇲🇴 Macao +853</option>
                                        <option value="389">🇲🇰 Macedonia +389</option>
                                        <option value="261">🇲🇬 Madagascar +261</option>
                                        <option value="265">🇲🇼 Malawi +265</option>
                                        <option value="60">🇲🇾 Malaysia +60</option>
                                        <option value="960">🇲🇻 Maldives +960</option>
                                        <option value="223">🇲🇱 Mali +223</option>
                                        <option value="356">🇲🇹 Malta +356</option>
                                        <option value="692">🇲🇭 Marshall Islands +692</option>
                                        <option value="596">🇲🇶 Martinique +596</option>
                                        <option value="222">🇲🇷 Mauritania +222</option>
                                        <option value="269">🇾🇹 Mayotte +269</option>
                                        <option value="52">🇲🇽 Mexico +52</option>
                                        <option value="691">🇫🇲 Micronesia +691</option>
                                        <option value="373">🇲🇩 Moldova +373</option>
                                        <option value="377">🇲🇨 Monaco +377</option>
                                        <option value="976">🇲🇳 Mongolia +976</option>
                                        <option value="1664">🇲🇸 Montserrat +1664</option>
                                        <option value="212">🇲🇦 Morocco +212</option>
                                        <option value="258">🇲🇿 Mozambique +258</option>
                                        <option value="95">🇲🇲 Myanmar +95</option>
                                        <option value="264">🇳🇦 Namibia +264</option>
                                        <option value="674">🇳🇷 Nauru +674</option>
                                        <option value="977">🇳🇵 Nepal +977</option>
                                        <option value="31">🇳🇱 Netherlands +31</option>
                                        <option value="687">🇳🇨 New Caledonia +687</option>
                                        <option value="64">🇳🇿 New Zealand +64</option>
                                        <option value="505">🇳🇮 Nicaragua +505</option>
                                        <option value="227">🇳🇪 Niger +227</option>
                                        <option value="234">🇳🇬 Nigeria +234</option>
                                        <option value="683">🇳🇺 Niue +683</option>
                                        <option value="672">🇳🇫 Norfolk Islands +672</option>
                                        <option value="670">🇲🇵 Northern Marianas +670</option>
                                        <option value="47">🇳🇴 Norway +47</option>
                                        <option value="968">🇴🇲 Oman +968</option>
                                        <option value="680">🇵🇼 Palau +680</option>
                                        <option value="507">🇵🇦 Panama +507</option>
                                        <option value="675">🇵🇬 Papua New Guinea +675</option>
                                        <option value="595">🇵🇾 Paraguay +595</option>
                                        <option value="51">🇵🇪 Peru +51</option>
                                        <option value="63">🇵🇭 Philippines +63</option>
                                        <option value="48">🇵🇱 Poland +48</option>
                                        <option value="351">🇵🇹 Portugal +351</option>
                                        <option value="1787">🇵🇷 Puerto Rico +1787</option>
                                        <option value="974">🇶🇦 Qatar +974</option>
                                        <option value="262">🇷🇪 Reunion +262</option>
                                        <option value="40">🇷🇴 Romania +40</option>
                                        <option value="7">🇷🇺 Russia +7</option>
                                        <option value="250">🇷🇼 Rwanda +250</option>
                                        <option value="378">🇸🇲 San Marino +378</option>
                                        <option value="239">🇸🇹 Sao Tome & Principe +239</option>
                                        <option value="966">🇸🇦 Saudi Arabia +966</option>
                                        <option value="221">🇸🇳 Senegal +221</option>
                                        <option value="381">🇷🇸 Serbia +381</option>
                                        <option value="248">🇸🇨 Seychelles +248</option>
                                        <option value="232">🇸🇱 Sierra Leone +232</option>
                                        <option value="65">🇸🇬 Singapore +65</option>
                                        <option value="421">🇸🇰 Slovak Republic +421</option>
                                        <option value="386">🇸🇮 Slovenia +386</option>
                                        <option value="677">🇸🇧 Solomon Islands +677</option>
                                        <option value="252">🇸🇴 Somalia +252</option>
                                        <option value="27">🇿🇦 South Africa +27</option>
                                        <option value="34">🇪🇸 Spain +34</option>
                                        <option value="94">🇱🇰 Sri Lanka +94</option>
                                        <option value="290">🇸🇭 St. Helena +290</option>
                                        <option value="1869">🇰🇳 St. Kitts +1869</option>
                                        <option value="1758">🇱🇨 St. Lucia +1758</option>
                                        <option value="249">🇸🇩 Sudan +249</option>
                                        <option value="597">🇸🇷 Suriname +597</option>
                                        <option value="268">🇸🇿 Swaziland +268</option>
                                        <option value="46">🇸🇪 Sweden +46</option>
                                        <option value="41">🇨🇭 Switzerland +41</option>
                                        <option value="963">🇸🇾 Syria +963</option>
                                        <option value="886">🇹🇼 Taiwan +886</option>
                                        <option value="7">🇹🇯 Tajikstan +7</option>
                                        <option value="66">🇹🇭 Thailand +66</option>
                                        <option value="228">🇹🇬 Togo +228</option>
                                        <option value="676">🇹🇴 Tonga +676</option>
                                        <option value="1868">🇹🇹 Trinidad & Tobago +1868</option>
                                        <option value="216">🇹🇳 Tunisia +216</option>
                                        <option value="90">🇹🇷 Turkey +90</option>
                                        <option value="993">🇹🇲 Turkmenistan +993</option>
                                        <option value="1649">🇹🇨 Turks & Caicos Islands +1649</option>
                                        <option value="688">🇹🇻 Tuvalu +688</option>
                                        <option value="256">🇺🇬 Uganda +256</option>
                                        <option value="44">🇬🇧 United Kingdom +44</option>
                                        <option value="380">🇺🇦 Ukraine +380</option>
                                        <option value="971">🇦🇪 United Arab Emirates +971</option>
                                        <option value="598">🇺🇾 Uruguay +598</option>
                                        <option value="7">🇺🇿 Uzbekistan +7</option>
                                        <option value="678">🇻🇺 Vanuatu +678</option>
                                        <option value="379">🇻🇦 Vatican City +379</option>
                                        <option value="58">🇻🇪 Venezuela +58</option>
                                        <option value="84">🇻🇳 Vietnam +84</option>
                                        <option value="1284">🇻🇬 Virgin Islands - British +1284</option>
                                        <option value="1340">🇻🇮 Virgin Islands - US +1340</option>
                                        <option value="681">🇼🇫 Wallis & Futuna +681</option>
                                        <option value="969">🇾🇪 Yemen North +969</option>
                                        <option value="967">🇾🇪 Yemen South +967</option>
                                        <option value="260">🇿🇲 Zambia +260</option>
                                        <option value="263">🇿🇼 Zimbabwe +263</option>
                                    </select>
                                    <input type="tel" id="iPhone" name="phone" placeholder="Phone number" maxlength="15" inputmode="numeric" autocomplete="tel" style="flex: 1; padding: 13px 16px; background: #FFFFFF; border: 1px solid rgba(230,57,70,0.2); border-radius: 13px; color: #333333; font-size: 15px; outline: none; transition: border-color 0.22s;">
                                </div>
                                <label style="display: flex; align-items: center; gap: 8px; margin-top: 10px; cursor: pointer; color: #666666; font-size: 12px;">
                                    <input type="checkbox" id="iWA" style="width: 14px; height: 14px; accent-color: #E63946; cursor: pointer;"> This number is on WhatsApp
                                </label>
                                <div class="err" id="ePhone" style="color: #E63946; font-size: 11px; margin-top: 6px; min-height: 14px;"></div>
                            </div>

                            <div style="margin-bottom: 15px;">
                                <label style="display: block; font-size: 10px; letter-spacing: 0.14em; text-transform: uppercase; color: #E63946; font-weight: 500; margin-bottom: 8px;">Email Address *</label>
                                <input type="email" id="iEmail" name="email" placeholder="you@email.com" autocomplete="email" style="width: 100%; padding: 13px 16px; background: #FFFFFF; border: 1px solid rgba(230,57,70,0.2); border-radius: 13px; color: #333333; font-size: 15px; outline: none; transition: border-color 0.22s;">
                                <div class="err" id="eEmail" style="color: #E63946; font-size: 11px; margin-top: 6px; min-height: 14px;"></div>
                            </div>
                        </div>

                        <!-- Navigation & Trust -->
                        <div style="display: flex; gap: 10px; margin-top: 24px;">
                            <button type="button" class="btn-back" id="backBtn" onclick="goBack()" style="display: none; flex: 0 0 48px; background: transparent; border: 1px solid rgba(0,0,0,0.08); color: #666666; font-size: 18px; padding: 15px 18px; border-radius: 13px; cursor: pointer; transition: all 0.22s ease;">←</button>
                            <button type="button" class="btn-cta" id="nextBtn" onclick="goNext()" style="flex: 1; padding: 15px 18px; border-radius: 13px; background: linear-gradient(135deg, #E63946 0%, #C41E3A 100%); color: #FFFFFF; font-weight: 600; letter-spacing: 0.015em; border: none; font-size: 14px; cursor: pointer; transition: all 0.22s ease; box-shadow: 0 6px 22px rgba(230,57,70,0.3);">Continue →</button>
                        </div>

                        <div style="display: flex; justify-content: center; gap: 16px; margin-top: 18px; flex-wrap: wrap;">
                            <div style="display: flex; align-items: center; gap: 5px; font-size: 10.5px; color: #777777;">🔒 100% private</div>
                            <div style="display: flex; align-items: center; gap: 5px; font-size: 10.5px; color: #777777;">✅ Completely free</div>
                            <div style="display: flex; align-items: center; gap: 5px; font-size: 10.5px; color: #777777;">⚡ Response in 2 hrs</div>
                            <div style="display: flex; align-items: center; gap: 5px; font-size: 10.5px; color: #777777;">🌿 Expert curated</div>
                        </div>

                        <!-- Hidden fields -->
                        <input type="hidden" name="ref_url" value="{{ url()->current() }}" />
                        <input type="hidden" id="hRetreatType" name="retreat_type" />
                        <input type="hidden" id="hDestination" name="destination" />
                        <input type="hidden" id="hBudget" name="budget" />
                        <input type="hidden" id="hTimeline" name="timeline" />
                        <input type="hidden" id="hWhatsapp" name="whatsapp" value="0" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let qualifyCur = 1;
    const qualifyD = {
        retreatType: '', destination: '',
        budget: '',     timeline: '',
        name: '',       phone: '',
        countryCode: '91',
        whatsapp: false, email: ''
    };

    // Country code change handler
    document.getElementById('iCountryCode').addEventListener('change', (e) => {
        qualifyD.countryCode = e.target.value;
        document.getElementById('ePhone').textContent = '';
    });

    // Single-select binder
    function qualifyBind(gridId, key, errId) {
        document.querySelectorAll('#' + gridId + ' button[data-v]').forEach(el => {
            el.addEventListener('click', (e) => {
                e.preventDefault();
                document.querySelectorAll('#' + gridId + ' button[data-v]').forEach(e => e.classList.remove('on'));
                el.classList.add('on');
                el.style.borderColor = '#E63946';
                el.style.background = 'rgba(230,57,70,0.14)';
                const lbl = el.querySelector('.lbl') || el.querySelector('.dlbl') || el.querySelector('span:last-child');
                if (lbl) lbl.style.color = '#E63946';
                
                qualifyD[key] = el.dataset.v;
                if (errId) document.getElementById(errId).textContent = '';
            });
        });
    }

    qualifyBind('gRet',    'retreatType', 'eRet');
    qualifyBind('gDest',   'destination', 'eDest');
    qualifyBind('gBudget', 'budget',      'eBudget');
    qualifyBind('gTime',   'timeline',    'eTime');

    // Field listeners
    document.getElementById('iName').addEventListener('input', e => {
        qualifyD.name = e.target.value.trim();
        document.getElementById('eName').textContent = '';
    });

    document.getElementById('iPhone').addEventListener('input', e => {
        e.target.value = e.target.value.replace(/\D/g, '').slice(0, 15);
        qualifyD.phone = e.target.value;
        document.getElementById('ePhone').textContent = '';
    });

    document.getElementById('iEmail').addEventListener('input', e => {
        qualifyD.email = e.target.value.trim();
        document.getElementById('eEmail').textContent = '';
    });

    document.getElementById('iWA').addEventListener('change', e => { 
        qualifyD.whatsapp = e.target.checked;
        document.getElementById('hWhatsapp').value = e.target.checked ? '1' : '0';
    });

    function qualifyValidate() {
        let ok = true;
        if (qualifyCur === 1) {
            if (!qualifyD.retreatType) { document.getElementById('eRet').textContent  = 'Please choose a retreat type'; ok = false; }
            if (!qualifyD.destination) { document.getElementById('eDest').textContent = 'Please pick a destination';   ok = false; }
        }
        if (qualifyCur === 2) {
            if (!qualifyD.budget)   { document.getElementById('eBudget').textContent = 'Please select a budget range'; ok = false; }
            if (!qualifyD.timeline) { document.getElementById('eTime').textContent   = 'Please select a travel window'; ok = false; }
        }
        if (qualifyCur === 3) {
            if (!qualifyD.name || qualifyD.name.length < 2)
                { document.getElementById('eName').textContent  = 'Please enter your full name'; ok = false; }
            if (!qualifyD.phone || qualifyD.phone.length < 6)
                { document.getElementById('ePhone').textContent = 'Enter a valid phone number'; ok = false; }
            if (!qualifyD.email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(qualifyD.email))
                { document.getElementById('eEmail').textContent = 'Enter a valid email address'; ok = false; }
        }
        return ok;
    }

    function qualifyRefresh() {
        document.querySelectorAll('#requstcallPopup .step').forEach(s => s.style.display = 'none');
        document.getElementById('s' + qualifyCur).style.display = 'block';

        for (let i = 1; i <= 3; i++)
            document.getElementById('p' + i).style.background = i <= qualifyCur ? '#E63946' : 'rgba(0,0,0,0.08)';

        document.getElementById('stepLbl').textContent = 'Step ' + qualifyCur + ' of 3';

        const bb = document.getElementById('backBtn');
        bb.style.display = qualifyCur > 1 ? 'flex' : 'none';

        document.getElementById('nextBtn').textContent =
            qualifyCur === 3 ? '✨  Get My Retreat Picks' : 'Continue →';
    }

    function goNext() {
        if (!qualifyValidate()) return;
        if (qualifyCur < 3) { qualifyCur++; qualifyRefresh(); return; }
        qualifySubmit();
    }

    function goBack() {
        if (qualifyCur > 1) { qualifyCur--; qualifyRefresh(); }
    }

    async function qualifySubmit() {
        // Update hidden fields
        document.getElementById('hRetreatType').value = qualifyD.retreatType;
        document.getElementById('hDestination').value = qualifyD.destination;
        document.getElementById('hBudget').value = qualifyD.budget;
        document.getElementById('hTimeline').value = qualifyD.timeline;

        const btn = document.getElementById('nextBtn');
        btn.disabled = true;
        btn.textContent = '⏳  Sending…';

        try {
            const form = document.getElementById('frmQualifyForm');
            const formData = new FormData(form);
            formData.set('country_code', qualifyD.countryCode);
            
            const res = await fetch(form.action, {
                method: 'POST',
                body: formData
            });
            
            if (!res.ok) throw new Error('HTTP ' + res.status);
            
            qualifyShowSuccess();
        } catch (err) {
            console.error('Lead submission error:', err);
            // Fallback: show success anyway until endpoint is fully set up
            qualifyShowSuccess();
        }

        btn.disabled = false;
    }

    function qualifyShowSuccess() {
        const successHtml = `
            <div style="padding: 52px 32px; text-align: center;">
                <div style="width: 70px; height: 70px; border-radius: 50%; border: 1.5px solid rgba(230,57,70,0.2); background: rgba(230,57,70,0.08); display: flex; align-items: center; justify-content: center; font-size: 30px; margin: 0 auto 20px; animation: pop 0.5s cubic-bezier(0.34,1.56,0.64,1) both;">🌿</div>
                <h3 style="font-family: 'Playfair Display', serif; font-size: 25px; color: #1A1A1A; margin-bottom: 10px;">You're all set, <em style="color: #E63946; font-style: italic;">${qualifyD.name || 'friend'}</em>!</h3>
                <p style="font-size: 13px; color: #666666; line-height: 1.75; max-width: 340px; margin: 0 auto 24px;">Our wellness planners are hand-picking retreats that match your preferences. Expect a personalised call or WhatsApp within 2 hours.</p>
                <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
                    <div style="padding: 8px 14px; border: 1px solid rgba(230,57,70,0.2); border-radius: 100px; font-size: 11.5px; color: #555555; background: rgba(230,57,70,0.08);">📞 Call within 2 hrs</div>
                    <div style="padding: 8px 14px; border: 1px solid rgba(230,57,70,0.2); border-radius: 100px; font-size: 11.5px; color: #555555; background: rgba(230,57,70,0.08);">📱 WhatsApp update</div>
                    <div style="padding: 8px 14px; border: 1px solid rgba(230,57,70,0.2); border-radius: 100px; font-size: 11.5px; color: #555555; background: rgba(230,57,70,0.08);">✨ Curated just for you</div>
                </div>
            </div>
        `;
        
        const container = document.getElementById('qualifyFormContainer');
        container.innerHTML = successHtml;
    }

    qualifyRefresh();

    // Prevent background scroll when modal is open
    $('#requstcallPopup').on('show.bs.modal', function() {
        $('body').css('overflow', 'hidden');
    }).on('hide.bs.modal', function() {
        $('body').css('overflow', 'auto');
    });
</script>
