<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

        body {

            font-family: 'Poppins', sans-serif;
            padding: 0 32px;
            max-width: 600px;
            margin: 10% auto;
            width: 100%;
        }

        .mail-body {
            text-align: center;

        }

        .mail_verification_title {
            margin-top: 30px;
        }

        .mail_verification_title h2 {
            font-size: 24px;
            font-weight: 600;
            color: #000000;
            margin: 0;
        }

        .otp_code {
            font-size: 24px;
            font-weight: 300;
            color: #6E6E6E;
            margin: 10px 0;
            letter-spacing: 5px;
        }

        .opt_instruction {
            font-size: 14px;
            font-weight: 400;
            color: #1e1d1d;

        }

        .opt_instruction p {
            margin: 0;
        }

        .footer_inst {
            margin-top: 50px;
        }

        .footer_inst p {
            font-size: 14px;
            font-weight: 400;
            color: #1e1d1d;
            margin: 0;
        }

        .footer_inst a {
            font-size: 14px;
            font-weight: 600;
            color: #00a5ff;
            text-decoration: none;
        }

        .footer {
            padding-top: 40px;
            border-top: 1px solid #e5e5e5;
            margin-top: 50px;
        }

        .footer p {
            font-size: 14px;
            font-weight: 400;
            color: #1e1d1d;
            margin: 0;
        }

        .footer a {
            font-size: 14px;
            font-weight: 600;
            color: #F25A2B;
            text-decoration: none;
        }
    </style>
    <title>Document</title>
</head>
<body>
<div class="mail-body">
    <img src="{{asset('img/mail-logo.png')}}" alt="logo">
    <div class="mail_verification_title">
        <h2>Verification code</h2>
    </div>
    <div class="otp_code">
        {{ $otp }}
    </div>
    <div class="opt_instruction">
        <p>Here is your OTP verification code.</p>
        <p>it will expire in {{$expireTime}} minutes.</p>
    </div>
</div>
<div class="footer_inst">
    <p>if you don't try to sign in just now,please <a href="#">change your password</a> to protect your account.</p>
</div>
<div class="footer">
    <p>
        Sent by <a href="#">Hello Tech</a>
    </p>
</div>
</body>
</html>
