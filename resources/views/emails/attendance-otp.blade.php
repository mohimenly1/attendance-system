<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Verification Code</title>
</head>
<body>
    <h2>Attendance Verification</h2>

    <p>You were marked absent for a lecture.</p>

    <p>Please use the following OTP code to confirm your attendance:</p>

    <h1 style="letter-spacing: 3px;">
        {{ $code }}
    </h1>

    <p>This code will expire in a few minutes.</p>

    <p>If you did not attend the lecture, ignore this email.</p>
</body>
</html>
