<!DOCTYPE html>
<html>
<head>
    <title>Selamat Datang di ESA PIPA</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #333333;
        }
        .content {
            line-height: 1.6;
            color: #555555;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 0.9em;
            color: #888888;
        }
        .btn {
            display: inline-block;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
            margin-top: 15px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Selamat Datang, {{ $user->name }}!</h1>
        </div>
        <div class="content">
            <p>Terima kasih atas pendaftaranmu. Akunmu telah berhasil dibuat.</p>
            <p>Ini adalah akun untuk kamu login:</p>
            <ul>
                <li><strong>Email:</strong> {{ $user->email }}</li>
                <li><strong>Password:</strong> {{ $password }}</li>
            </ul>
            <p>Kamu dapat login dengan meng-klik tombol di bawah:</p>
            <a href="{{ route('login') }}" class="btn">Log In</a>
            <p>Kami merekomendasikan kamu untuk ganti password demi alasan keamanan.</p>
        </div>
        <div class="footer">
            <p>Jika kamu memiliki pertanyaan, silakan hubungi kami di support@[yourdomain].com.</p>
            <p>Terima kasih, <br>Tim [Your Application Name]</p>
        </div>
    </div>
</body>
</html>
