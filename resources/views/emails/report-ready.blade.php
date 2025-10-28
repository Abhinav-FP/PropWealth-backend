<!DOCTYPE html>
<html>

<head>
  <title>Your SuburbTrends Report is Ready!</title>
</head>

<body>
  <h2>Hello {{ $user->first_name }},</h2>
  <p>Your SuburbTrends report for <strong>{{ $suburb }}</strong> has been generated successfully!</p>
  <p>Please find your report attached to this email.</p>
  <p>Thank you for using SuburbTrends!</p>
  <p>Best regards,<br>The SuburbTrends</p>
</body>

</html>