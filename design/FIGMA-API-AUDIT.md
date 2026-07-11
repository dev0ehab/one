# مراجعة Figma ↔ API (تصدير PNG)

المصدر: `design/user/*.png` و `design/provider/*.png` — مايو 2026.

## العميل (design/user)

| ملف التصميم | الشاشات | API | ملاحظات |
|-------------|---------|-----|---------|
| `Splash.png` | شاشة البداية | `GET settings`, `GET client/app-intro` | UI |
| `on boarding.png` | تعريف بالتطبيق | `GET client/app-intro` | UI |
| `registration & login.png` | تسجيل، OTP، دخول، نسيت كلمة المرور | `POST register`, `PUT activate`, `POST login`, `password/*` | بدون `verify-login` |
| `home.png` | قائمة مقدمي الخدمة، فلتر، خريطة، تفاصيل، مفضلة | `GET providers`, `providers/map`, `providers/categories`, `favorites` | Zoom/أيقونة النشاط على الخريطة = UI |
| `حجوزاتي.png` | تبويبات، تفاصيل، دفع، فاتورة إضافية، تقييم | `bookings/*`, `additional-invoice/*`, `POST rate` | `tab=current\|past\|cancelled\|pending_payment` |
| `Notification.png` | قائمة، فارغ، حذف، حذف الكل | `notifications`, `count-notifications`, `delete-*`, `switch-notify` | `created_at`: `d/m/Y - g:i` + مساء/صباحاً |
| `more.png` | ملف شخصي، إعدادات، شكوى، محفظة، استفسارات، حذف حساب | `profile`, `complains`, `wallet`, `inquiries`, `delete-account`, `terms/privacy` | بدون loyalty؛ بدون شات |

## مقدم الخدمة (design/provider)

| ملف التصميم | الشاشات | API | ملاحظات |
|-------------|---------|-----|---------|
| `Splash.png` | ترحيب | `GET settings`, `GET provider/app-intro` | UI |
| `on boarding.png` | onboarding | `GET provider/app-intro` | UI |
| `registration & login.png` | تسجيل، دخول | `POST register`, `login`, `password/*` | |
| `home.png` | لوحة: رسائل إدارة، حجوزات، استفسارات، إعلانات، رسائل | **`GET provider/home`** | `admin_messages_count`, `bookings_count`, `inquiries_count`, `ads_count`, `messages_count` |
| `حجوزاتي.png` | اعلانات/حجوزات: قبول، رفض، فاتورة، إكمال | `provider/bookings/*` | |
| `Notification.png` | نفس العميل | `provider/notifications/*` | نفس تنسيق التاريخ |
| `more.png` | بروفايل، بنك، سحب، محفظة، تحديث بيانات | `profile`, `bank-account`, `wallet/*`, `update/profile` | بدون work-days في API |

## خارج نطاق تطبيق الموبايل (حسب TASKS)

- §9 شات — مؤجّل
- §12 عناوين محفوظة — غير في التصميم
- §6 بوابات دفع إضافية (Stripe, PayPal…) — غير في Figma
- §15 لوحة الإدارة — `routes/dashboard/*`
- §18 الموقع التعريفي — `IntroController` + `intro_site` views

## Postman

الكولكشن: `postman/One.postman_collection.json` — يُرفع عبر `php scripts/push-postman-collection.php`.
