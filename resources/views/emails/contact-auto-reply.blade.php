<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #006400;
            color: white;
            padding: 30px;
            text-align: center;
        }

        .content {
            background-color: #f9f9f9;
            padding: 30px;
            margin-top: 20px;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 12px;
        }

        .button {
            background-color: #006400;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            display: inline-block;
            margin: 20px 0;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Thank You for Contacting Us</h1>
        </div>

        <div class="content">
            <p>Dear {{ $contact->name }},</p>

            <p>Thank you for reaching out to the Nigeria Police Trust Fund. We have received your message and appreciate
                you taking the time to contact us.</p>

            <p><strong>Your submission details:</strong></p>
            <ul>
                <li><strong>Subject:</strong> {{ $contact->subject }}</li>
                <li><strong>Date:</strong> {{ $contact->created_at->format('F j, Y') }}</li>
            </ul>

            <p>Our team will review your message and respond as soon as possible. We typically respond within 1-2
                business days.</p>

            <p>If your matter is urgent, please contact us directly:</p>
            <ul>
                <li><strong>Phone:</strong> +234 (8147692468) or +234 (9061268054)</li>
                <li><strong>Email:</strong> info@nptf.gov.ng</li>
                <li><strong>Office Hours:</strong> Monday - Friday, 9:00 AM - 5:00 PM</li>
            </ul>

            <p>Best regards,<br>
                <strong>Nigeria Police Trust Fund</strong>
            </p>
        </div>

        <div class="footer">
            <p>Nigeria Police Trust Fund<br>
                38, Agadez Crescent, Wuse II, FCT-Abuja, Nigeria<br>
                Email: info@nptf.gov.ng | Phone: +234 (8147692468)</p>

            <p style="margin-top: 20px; font-size: 11px; color: #999;">
                This is an automated message. Please do not reply to this email.
            </p>
        </div>
    </div>
</body>

</html>