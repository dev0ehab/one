<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الشروط والأحكام</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 28px;
        }
        .welcome {
            background-color: #e7f3ff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
            border-right: 4px solid #007bff;
        }
        .terms-content {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .user-info {
            background-color: #fff3cd;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-right: 4px solid #ffc107;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>الشروط والأحكام</h1>
            <p>Terms and Conditions</p>
        </div>

        <div class="welcome">
            <h2>مرحباً {{ $user->name }}</h2>
            <p>نرحب بك في منصة PetroWallet. يرجى مراجعة الشروط والأحكام التالية بعناية.</p>
        </div>

        <div class="user-info">
            <strong>معلومات الحساب:</strong><br>
            الاسم: {{ $user->name }}<br>
            البريد الإلكتروني: {{ $user->email }}<br>
            رقم الهاتف: {{ $user->country_code }}{{ $user->phone }}<br>
            نوع الحساب: {{ class_basename($user) === 'provider' ? 'خدمة' : 'شركة' }}
        </div>

        @if($terms)
        <div class="terms-content">
            <h3>الشروط والأحكام:</h3>
            {!! $terms->getTranslation('content', 'ar') !!}
        </div>
        @else
        <div class="terms-content">
            <h3>الشروط والأحكام:</h3>
            <p>نعتذر، لا يمكن العثور على الشروط والأحكام حالياً. يرجى التواصل معنا للحصول على نسخة محدثة.</p>
        </div>
        @endif

        <div class="footer">
            <p>شكراً لانضمامك إلينا</p>
            <p>PetroWallet Team</p>
            <p>هذا بريد إلكتروني تلقائي، يرجى عدم الرد عليه</p>
        </div>
    </div>
</body>
</html>
