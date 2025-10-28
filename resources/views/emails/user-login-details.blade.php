<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Welcome to SuburbTrends — Your Login Details</title>
  <!-- Google Font: Montserrat -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Montserrat', Arial, sans-serif;
      color: #333333;
      font-size: 14px;
      line-height: 1.6;
      margin: 0;
      padding: 20px;
    }

    p {
      margin: 0 0 12px;
    }

    ul {
      padding-left: 18px;
    }

    li {
      margin-bottom: 6px;
    }

    strong {
      font-weight: 600;
    }
  </style>
</head>

<body>
  <p>Hello {{ $user->first_name }},</p>

  <p>Thanks for requesting a SuburbTrends report. We’ve created your account so you can receive and download reports.</p>

  <p><strong>Your login details:</strong></p>
  <ul>
    <li><strong>Email:</strong> {{ $user->email }}</li>
    <li><strong>Password:</strong> {{ $password }}</li>
  </ul>

  <p>You may be asked to verify your mobile number. Enter the 6‑digit OTP sent to your phone. The OTP expires in 10 minutes.</p>

  <p>Log in here: <a href="{{ url('/login') }}" style="color:#EE2E67; text-decoration:none;">{{ url('/login') }}</a></p>

  <p>For security, please change your password after your first login.</p>

  <p>Regards,<br>SuburbTrends</p>
</body>

</html>