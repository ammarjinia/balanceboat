<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 20px auto; padding: 20px; background: #f9f9f9; border: 1px solid #ddd; border-radius: 8px; }
        .header { background: linear-gradient(135deg, #C9933A 0%, #7A4F14 100%); color: white; padding: 20px; border-radius: 8px 8px 0 0; text-align: center; }
        .header h2 { margin: 0; }
        .content { background: white; padding: 20px; border-radius: 0 0 8px 8px; }
        .detail-row { margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .detail-row:last-child { border-bottom: none; }
        .label { font-weight: bold; color: #C9933A; }
        .footer { text-align: center; font-size: 12px; color: #666; margin-top: 20px; padding-top: 10px; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>🌿 New Quick Lead Received</h2>
        </div>
        <div class="content">
            <p>A new retreat seekr has submitted their preferences through the Quick Lead form:</p>
            
            <div class="detail-row">
                <span class="label">Name:</span> {{ $name ?? 'N/A' }}
            </div>
            
            <div class="detail-row">
                <span class="label">Email:</span> {{ $email ?? 'N/A' }}
            </div>
            
            <div class="detail-row">
                <span class="label">Phone:</span> +{{ $phone ?? 'N/A' }} (WhatsApp: {{ $whatsapp ?? 'No' }})
            </div>
            
            <div class="detail-row">
                <span class="label">Retreat Type:</span> {{ ucfirst(str_replace('_', ' ', $retreat_type ?? 'N/A')) }}
            </div>
            
            <div class="detail-row">
                <span class="label">Preferred Location:</span> {{ ucfirst(str_replace('_', ' ', $destination ?? 'N/A')) }}
            </div>
            
            <div class="detail-row">
                <span class="label">Budget Range:</span> {{ ucfirst(str_replace('_', ' – ', str_replace('_', ' ₹', $budget)) ?? 'N/A') }}
            </div>
            
            <div class="detail-row">
                <span class="label">Travel Timeline:</span> {{ ucfirst(str_replace('_', ' ', $timeline ?? 'N/A')) }}
            </div>
            
            <div class="detail-row">
                <span class="label">Source:</span> {{ $source ?? 'N/A' }}
            </div>
            
            <p style="margin-top: 20px; padding: 15px; background: #f0f7ff; border-left: 4px solid #C9933A; border-radius: 4px;">
                <strong>Action Required:</strong> Please contact this lead within 2 hours with personalized retreat recommendations based on their preferences.
            </p>
        </div>
        <div class="footer">
            <p>BalanceBoat Lead Management System | {{ date('Y-m-d H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
