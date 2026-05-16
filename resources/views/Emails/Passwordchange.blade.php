<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>OTP for Password Change</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .email-box {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        .otp {
            font-size: 24px;
            color: #172be0;
            font-weight: bold;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="email-box">
        <h2>Password Change Request</h2>
        <p>Hello,</p>
        <p>Your One-Time Password (OTP) for changing your password is:</p>
        <p class="otp">{{ $otp }}</p>
        <p>If you didn’t request this, please ignore this email.</p>
        <p>Thanks</p>
    </div>
</body>
</html>
