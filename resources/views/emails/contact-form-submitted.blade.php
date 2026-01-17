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
            padding: 20px;
            text-align: center;
        }

        .content {
            background-color: #f9f9f9;
            padding: 30px;
            margin-top: 20px;
        }

        .info-row {
            margin: 15px 0;
        }

        .label {
            font-weight: bold;
            color: #006400;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>New Contact Form Submission</h1>
        </div>

        <div class="content">
            <p>You have received a new contact form submission from the NPTF website:</p>

            <div class="info-row">
                <span class="label">Name:</span> {{ $contact->name }}
            </div>

            <div class="info-row">
                <span class="label">Email:</span> {{ $contact->email }}
            </div>

            <div class="info-row">
                <span class="label">Phone:</span> {{ $contact->phone }}
            </div>

            <div class="info-row">
                <span class="label">Subject:</span> {{ $contact->subject }}
            </div>

            <div class="info-row">
                <span class="label">Message:</span><br>
                <p style="background: white; padding: 15px; border-left: 4px solid #006400;">
                    {{ $contact->message }}
                </p>
            </div>

            <div class="info-row">
                <span class="label">Submitted:</span> {{ $contact->created_at->format('F j, Y \a\t g:i A') }}
            </div>
        </div>

        <div class="footer">
            <p>Nigeria Police Trust Fund<br>
                38, Agadez Crescent, Wuse II, FCT-Abuja, Nigeria</p>
        </div>
    </div>
</body>

</html>