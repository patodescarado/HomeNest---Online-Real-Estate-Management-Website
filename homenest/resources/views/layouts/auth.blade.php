
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - HomeNest</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Be Vietnam Pro', sans-serif;
            background: #f4f6f3;
            color: #096c65;
            min-height: 100vh;
        }

        .auth-container {
            display: flex;
            min-height: 100vh;
            padding: 24px;
            gap: 24px;
        }

        .auth-visual {
            flex: 1;
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            background:
                linear-gradient(180deg, rgba(10,35,23,.1), rgba(10,35,23,.8)),
                url('https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=1200');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: flex-end;
            padding: 55px;
            color: white;
        }

        .visual-content {
            max-width: 540px;
        }

        .visual-content h1 {
            font-size: 38px;
            line-height: 1.4;
            margin-bottom: 15px;
        }

        .visual-content p {
            font-size: 15px;
            line-height: 1.8;
            color: #e4e9e5;
        }

        .auth-form-area {
            width: 480px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 35px;
        }

        .auth-form {
            width: 100%;
            max-width: 390px;
        }

        .logo {
            font-size: 27px;
            font-weight: 800;
            color: #096c65;
            margin-bottom: 45px;
        }

        .logo span {
            color: #096c65;
        }

        .auth-form h2 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #89918b;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 19px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 9px;
        }

        .form-group input {
            width: 100%;
            height: 49px;
            border: 1px solid #dfe5df;
            border-radius: 9px;
            padding: 0 15px;
            outline: none;
            font-family: inherit;
            font-size: 13px;
            background: white;
            transition: .2s;
        }

        .form-group input:focus {
            border-color: #397a50;
            box-shadow: 0 0 0 3px rgba(57,122,80,.1);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            margin: 8px 0 24px;
        }

        .form-options a, .auth-switch a {
            color: #096c65;
            text-decoration: none;
            font-weight: 600;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .remember input {
            accent-color: #096c65;
        }

        .submit-btn {
            width: 100%;
            height: 49px;
            border: none;
            border-radius: 9px;
            background: #096c65;
            color: white;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: .2s;
        }

        .submit-btn:hover {
            background: #19472d;
        }

        .auth-switch {
            text-align: center;
            font-size: 13px;
            color: #89918b;
            margin-top: 25px;
        }

        .error-message {
            color: #dc4545;
            font-size: 12px;
            margin-top: 6px;
        }

        .alert-error {
            background: #fff0f0;
            color: #c0392b;
            padding: 12px;
            border-radius: 8px;
            font-size: 12px;
            margin-bottom: 18px;
        }

        @media(max-width: 900px) {
            .auth-visual {
                display: none;
            }

            .auth-form-area {
                width: 100%;
                margin: auto;
            }
        }

        @media(max-width: 480px) {
            .auth-container {
                padding: 10px;
            }

            .auth-form-area {
                padding: 20px;
            }

            .logo {
                margin-bottom: 35px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-visual">
            <div class="visual-content">
                <h1>Không gian sống lý tưởng bắt đầu từ đây.</h1>
                <p>
                    Hãy ăn trộm tiền để trải nghiệm những căn hộ tuyệt vời nhất được rao bán tại HomeNest. Chúng tôi cam kết mang đến cho bạn những trải nghiệm sống tốt nhất, từ thiết kế hiện đại đến tiện nghi đầy đủ.
                </p>
            </div>
        </div>

        <main class="auth-form-area">
            <div class="auth-form">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
