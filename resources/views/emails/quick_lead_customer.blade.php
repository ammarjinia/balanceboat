<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 20px auto; padding: 20px; background: #f9f9f9; border: 1px solid #ddd; border-radius: 8px; }
        .header { background: linear-gradient(135deg, #C9933A 0%, #7A4F14 100%); color: white; padding: 30px 20px; border-radius: 8px 8px 0 0; text-align: center; }
        .header h2 { margin: 0; font-size: 24px; }
        .header p { margin: 10px 0 0 0; font-size: 14px; opacity: 0.9; }
        .content { background: white; padding: 30px 20px; border-radius: 0 0 8px 8px; }
        .greeting { font-size: 16px; margin-bottom: 20px; }
        .summary { background: #f0f7ff; padding: 20px; border-radius: 8px; border-left: 4px solid #C9933A; margin: 20px 0; }
        .summary-title { font-weight: bold; color: #C9933A; margin-bottom: 10px; }
        .summary-item { margin: 8px 0; padding-left: 20px; }
        .summary-item::before { content: "✓ "; color: #C9933A; font-weight: bold; margin-left: -20px; }
        .next-steps { margin: 30px 0; }
        .next-steps h3 { color: #C9933A; }
        .step { margin: 15px 0; padding-left: 25px; position: relative; }
        .step::before { content: attr(data-step); position: absolute; left: 0; top: 0; background: #C9933A; color: white; width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold; }
        .footer { text-align: center; font-size: 12px; color: #666; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; }
        .contact-info { background: #f9f9f9; padding: 15px; border-radius: 5px; margin-top: 20px; text-align: center; }
        .contact-info a { color: #C9933A; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>🌿 Welcome to BalanceBoat!</h2>
            <p>We're finding your perfect retreat...</p>
        </div>
        <div class="content">
            <p class="greeting">Hi {{ $name ?? 'Friend' }},</p>
            
            <p>Thank you for sharing your wellness preferences with us! We're excited to help you discover retreats that match exactly what you're looking for.</p>
            
            <div class="summary">
                <div class="summary-title">Your Search Profile:</div>
                <div class="summary-item">Looking for: <strong>{{ ucfirst(str_replace('_', ' ', $retreat_type ?? '')) }}</strong></div>
                <div class="summary-item">Destination: <strong>{{ ucfirst(str_replace('_', ' ', $destination ?? '')) }}</strong></div>
                <div class="summary-item">Budget: <strong>{{ ucfirst(str_replace('_', ' – ', str_replace('_', ' ₹', $budget ?? ''))) }}</strong> per person per day</div>
                <div class="summary-item">Timeline: <strong>{{ ucfirst(str_replace('_', ' ', $timeline ?? '')) }}</strong></div>
            </div>
            
            <div class="next-steps">
                <h3>What Happens Next?</h3>
                <div class="step" data-step="1"><strong>Our Experts Get to Work</strong><br>Your preferences are now in the hands of our wellness specialists who curate the best matching retreats.</div>
                <div class="step" data-step="2"><strong>Personal Consultation</strong><br>You'll receive a call or WhatsApp message within 2 hours with handpicked recommendations tailored to your needs.</div>
                <div class="step" data-step="3"><strong>Seamless Booking</strong><br>Once you've chosen your retreat, we'll handle all the details to make your booking smooth and hassle-free.</div>
            </div>
            
            <div class="contact-info">
                <p style="margin: 0 0 10px 0;">📞 Call us: <a href="tel:+917800080808">+91-7800080808</a></p>
                <p style="margin: 0;">💬 WhatsApp us for quick updates</p>
            </div>
            
            <p style="margin-top: 20px;">In the meantime, feel free to explore more retreats on our website or reach out with any questions!</p>
            
            <p style="margin-top: 20px;">Best wishes on your wellness journey,<br><strong>The BalanceBoat Team</strong> 🌿</p>
        </div>
        <div class="footer">
            <p>BalanceBoat - Curated Wellness Experiences | {{ date('Y') }}</p>
            <p>We're committed to helping you find your perfect retreat.</p>
        </div>
    </div>
</body>
</html>
