# ONE App — قائمة المهام الكاملة

> مُجمَّعة من: وثيقة التحليل الفني + وثائق تغيير المتطلبات (1-5) + استكشاف الكود

**أنواع المستخدمين في النطاق:** عميل (Client) + مقدم خدمة (Provider) فقط.

> **مراجعة التصميم (`design/user` + `design/provider`) — مايو 2026:**  
> API الموبايل يطابق Figma للعميل والمقدم. **مؤجّل:** شات (§9)، لويالتي. **أُزيل من API التطبيق:** `locations` CRUD، `verify-login`، `work-days`/`booking-service` للمقدم، شحن محفظة تحويل بنكي، `provider-categories`/`insurance`/`specializations` العامة. **خريطة §3:** سلوك Zoom/بطاقة — UI؛ الباكند يوفّر `providers/map` + `distance_km`.

**سير العمل لكل مهمة:**
1. تنفيذ المهمة (كود إن لزم)
2. **Postman أولاً** — إضافة/تحديث الطلبات في الكولكشن `54926212-e39f2c1c-6eea-4f44-8d11-d928212207ff` **قبل** سؤالك عن الموافقة (انظر `.postman/collection.json` + `postman/One.postman_collection.json`)
3. **Commit** على Git
4. أسألك: **هل تمام؟** — وبعد موافقتك ننتقل للمهمة التالية

**Postman:** Body كامل دائماً (JSON `raw` أو `form-data`) بقيم مثال — صيغة Collection v2.1 حتى يظهر الـ Body في التطبيق.

---

## 📦 1. إدارة المستخدمين والمصادقة

### العميل (Client)
- [x] تسجيل عميل جديد (اسم، جوال، كلمة مرور، الموافقة على الشروط — Figma 25:1249)
- [x] تفعيل الحساب + إعادة إرسال OTP (6 أرقام — Figma 25:972)
- [x] تسجيل دخول بالجوال + كلمة المرور (Figma 18:818)
- [x] نسيان كلمة المرور + إعادة التعيين (Figma 18:923)
- [x] تعديل الملف الشخصي (الاسم، الصورة — Figma 291:2671؛ العميل بدون بريد)
- [x] تغيير رقم الهاتف مع التحقق (OTP 6 أرقام — Figma تغيير رقم الجوال)
- [x] تغيير كلمة المرور
- [x] حذف الحساب مع إدخال سبب الحذف نصياً *(Figma سبب حذف الحساب — وثيقة تغيير #4)*
- [ ] ~~نظام نقاط الولاء~~ — **خارج التصميم** (لا تنفيذ)

### مقدم الخدمة (Provider)
- [x] تسجيل + تفعيل OTP (Figma مبسّط: اسم، جوال، email، كلمة مرور)
- [x] تسجيل دخول بكلمة المرور (بدون `verify-login`)
- [x] تعديل الملف عبر طلب موافقة الإدارة (`POST update/profile` → Entity Update)
- [x] حساب بنكي + سحب محفظة (`bank-account`, `wallet/withdraw`) — Figma more
- [ ] ~~`work-days` / `booking-service`~~ — **أُزيل من API** (غير في تصدير Figma المقدم)
- [x] ربط المقدم بالفئة (`category_id` — Admin + بروفايل)

---

## 🗺️ 2. البحث والتصفية واستعراض مقدمي الخدمة

- [x] عرض قائمة مقدمي الخدمة للعميل
- [x] **فلترة مقدمي الخدمة بـ:**
  - [x] الاشتراك في خدمة الحجز (`has_booking_service`) *(وثيقة تغيير #1)*
  - [x] التقييم (`min_rate`, `sort=highest_rate|lowest_rate`) *(وثيقة تغيير #1)*
  - [x] نوع النشاط (`category_id` / `activity_type_id`) *(وثيقة تغيير #1)*
- [x] البحث بالاسم أو الموقع (`search`)
- [x] استعراض صفحة تفاصيل مقدم الخدمة (الاسم، الصورة، التقييم، ساعات العمل)
- [x] إضافة مقدم خدمة للمفضلة / إزالته
- [x] عرض قائمة المفضلين

---

## 🗺️ 3. الخريطة (Map View)

- [x] عرض مقدمي الخدمة على الخريطة (`GET /api/client/providers/map` + `ProviderMapResource`)
- [x] عند التكبير / البطاقة / الضغط / موقع العميل — **UI فقط** (`design/user/home.png`؛ الباكند يوفّر `lat`/`lng`/`avg_rate`/`clinic_name`/`image`)

---

## 📅 4. نظام الحجز (Booking)

### الحجز الأساسي
- [x] إنشاء طلب حجز (اختيار مقدم الخدمة + الموعد)
- [x] ترتيب الحجوزات في "حجوزاتي" حسب التاريخ والوقت *(وثيقة تغيير #4)*
- [x] **رسالة تنبيهية للعميل** + `POST bookings/dismiss-empty-banner` *(وثيقة تغيير #1)*
- [x] إلغاء الحجز

### حالات طلب الحجز — جانب العميل
- [x] جديد (`new`)
- [x] قيد الدفع (`pending_payment`)
- [x] جاري تنفيذ الخدمة (`in_progress` + قبول/رفض فاتورة إضافية)
- [x] منتهي / مكتمل (`completed`)
- [x] ملغى (`cancelled`)

### حالات طلب الحجز — جانب مقدم الخدمة
- [x] قائمة الحجوزات + قبول / رفض (`new` → `pending_payment` أو `cancelled`)
- [x] إصدار فاتورة إضافية + قبول/رفض/دفع من العميل
- [x] إكمال الحجز (`POST bookings/{id}/complete`)

---

## 💬 5. نظام الاستفسارات (Inquiry)

- [x] إرسال استفسار من العميل مع تحديد **نوع النشاط** (`category_id`) *(وثيقة تغيير #4)*
- [x] إرسال الاستفسار إلى مقدمي الخدمة المتخصصين في نوع النشاط المحدد *(وثيقة تغيير #4)*
- [x] خيار الإرسال للـ **أقرب** (`target_type=nearest`) أو **الجميع** (`all`) *(وثيقة تغيير #4)*
  - الأقرب: المقدم الأقرب ضمن `inquiry_radius_km` من إعدادات الإدارة
  - الجميع: جميع المقدمين المعتمدين في التصنيف
- [x] قبول/رفض مقدم الخدمة للاستفسار
- [x] متابعة حالة الاستفسار (قوائم + تفاصيل + إلغاء)

---

## 💳 6. الدفع الإلكتروني (Payment)

### بوابة الدفع الأساسية
- [x] **دفع قيمة الخدمة الأساسية قبل التنفيذ** — `POST /api/client/bookings/{id}/pay` *(وثيقة تغيير #5)*
- [x] الحجز ينتقل إلى `in_progress` فقط بعد نجاح الدفع (`pending_payment` → `in_progress`) *(وثيقة تغيير #5)*
- [x] دعم الدفع من المحفظة (`payment_method=wallet`) أو البوابة (`online` / MyFatoorah) *(وثيقة تغيير #5)*

### بوابات الدفع المدمجة
- [ ] Stripe
- [ ] PayPal
- [x] MyFatoorah (حجوزات + شحن محفظة)
- [ ] HyperPay
- [ ] Telr
- [ ] Clickpay
- [ ] Paymob
- [ ] Alrajhi Bank

---

## 🧾 7. الفاتورة الإضافية (Additional Invoice)

- [x] **مقدم الخدمة** يمكنه إصدار فاتورة إضافية مرتبطة بالحجز أثناء التنفيذ (`in_progress`)
- [x] الفاتورة تحتوي: سبب + قيمة مالية
- [x] إرسال الفاتورة الإضافية للعميل مع إشعار (`UserNotifier` + Firebase)
- [x] **العميل** يمكنه:
  - [x] قبول الفاتورة → مهلة دفع من إعدادات الإدارة (`additional_invoice_payment_hours`)
  - [x] رفض الفاتورة (مع سبب اختياري)
  - [x] الدفع عبر المحفظة أو MyFatoorah (`POST …/additional-invoice/pay`)
- [x] عند الرفض: إعادة إصدار فاتورة جديدة من مقدم الخدمة
- [x] عند انتهاء المهلة: `payment_overdue` + إشعار للعميل
- [x] الحجز يبقى `in_progress` — الخدمة لا تُلغى
- [x] عمولة المنصة (`additional_invoice_commission_percent`) تُسجّل عند الدفع

---

## 💰 8. المحفظة والرصيد (Wallet)

- [x] عرض رصيد المحفظة للعميل / مقدم الخدمة (`GET wallet`)
- [x] سجل المعاملات (`GET wallet/transactions`)
- [x] شحن المحفظة — MyFatoorah (`POST wallet/charge`)
- [x] طلب سحب (مقدم الخدمة) — `POST wallet/withdraw` + Admin لاحقاً
- [ ] ~~تحويل بنكي / `charge-requests`~~ — **خارج تصميم التطبيق**
- [x] إشعار عند انخفاض الرصيد (`wallet_low_balance_threshold`)
- [x] الدفع من المحفظة (حجز + فاتورة إضافية)

---

## 💬 9. الدردشة والرسائل (Chat) — **مؤجّل (طلب صريح: لا تنفيذ الآن)**

- [ ] إنشاء غرفة دردشة بين العميل ومقدم الخدمة (Room)
- [ ] إرسال رسائل نصية
- [ ] إرسال ملفات (صور، فيديو، صوت، مستندات)
- [ ] إشعارات الرسائل الواردة
- [ ] حالة قراءة الرسائل (MessageNotification)

---

## ⭐ 10. التقييمات (Ratings)

- [x] العميل يقيّم مقدم الخدمة بعد إتمام الحجز (`POST bookings/{id}/rate`)
- [x] عرض متوسط التقييم على صفحة مقدم الخدمة (`avg_rate`, `total_rates`)
- [x] عرض متوسط التقييم في بطاقة الخريطة (`ProviderMapResource`)
- [x] عرض التقييم في قائمة مقدمي الخدمة (`ProvidersResource`)

---

## 🔔 11. الإشعارات (Notifications)

- [x] إشعارات Push (Firebase) للعميل ومقدم الخدمة (`UserNotifier` + `GlobalNotification`)
- [ ] إشعارات SMS (Unifonic, Jawaly, Msegat, Taqnyat, Yamamah, Zain…) — لوحة الإدارة فقط
- [ ] إشعارات بريد إلكتروني — لوحة الإدارة فقط
- [x] إشعارات داخل التطبيق (`GET notifications`, `count-notifications`, حذف)
- [x] إشعار عند وصول استفسار جديد (لمقدمي الخدمة المستهدفين)
- [x] إشعار عند وصول فاتورة إضافية *(وثيقة تغيير #5)*
- [x] إشعار عند انتهاء مهلة دفع الفاتورة الإضافية *(وثيقة تغيير #5)*
- [x] إشعار عند قبول / رفض طلب الحجز
- [x] إشعار عند دفع الحجز / إتمامه / إلغائه
- [x] إشعار عند قبول / رفض الاستفسار
- [x] إشعار عند الرد على الشكوى (من لوحة الإدارة)
- [x] معالجة الإشعارات عبر Queue Jobs (`SendNotification`)

---

## 📍 12. إدارة المواقع (Locations)

- [ ] ~~عناوين محفوظة / موقع رئيسي~~ — **خارج تصميم التطبيق** (`locations` API أُزيل)
- [x] البحث بالقرب — `lat`/`lng` + `sort=nearest` على `providers` و `map`
- [x] دول/مدن — Admin + `GET countries`/`cities` (اختياري للموبايل)

---

## 🏥 13. الفئات وأنواع النشاط

- [x] تبويبات النشاط للعميل — `GET /api/client/providers/categories` + `category_id` في الفلتر
- [x] ربط المقدم بالفئة — `category_id` على Provider
- [x] إدارة الفئات/الفرعية — **Admin فقط**
- [ ] ~~تأمين / تخصصات في API الموبايل~~ — **أُزيل** (غير في Figma)

---

## 🛡️ 14. الشكاوى والتواصل (Complaints & Contact)

- [x] تقديم شكوى + صور — `POST /api/client/complains`
- [x] قائمة/تفاصيل شكوى — `GET complains`, `GET complains/{id}`
- [x] رد الإدارة + إشعار — Admin `complains.completed`
- [x] تواصل — `GET /api/contact`, `POST /api/contact/store`, `POST /api/client/contact`
- [x] عرض الشكاوى في لوحة الإدارة

---

## 🛠️ 15. لوحة الإدارة (Admin Dashboard)

> موجودة في `routes/dashboard/*` (Blade + Controllers). ليست جزءاً من API الموبايل.

### إدارة المستخدمين
- [x] CRUD أدمنز + صلاحيات (`routes/dashboard/2024_12_31_0000_admin.php`)
- [x] CRUD عملاء (`2024_12_31_0000_client.php`)
- [x] CRUD مقدمي خدمة + موافقة (`2024_12_31_0000_provider.php`, `provider_update_requests`)
- [x] حذف جماعي (على معظم الجداول في الـ Controllers)

### إدارة المالية والعمولات
- [x] إعدادات عمولة / مهلة فاتورة / نطاق استفسارات (`SiteSettings` + Admin)
- [x] تسويات + شحن محفظة (لوحة الإدارة)

### إدارة المحتوى والإعدادات
- [x] فئات، دول/مدن، صفحات، FAQs، Intro، شركاء، socials، SMS، بوابة دفع (`routes/dashboard/*`)

### الأدوار والصلاحيات
- [x] Roles + Permissions (لوحة الإدارة)

### التقارير والتصدير
- [x] LogActivity + Excel/PDF/Import (`ExportPdfController`, admin exports)

---

## 🔄 16. طلبات التحديث (Entity Update Requests)

- [x] مقدم الخدمة يطلب تحديث → Admin يوافق/يرفض
- [x] `has_pending_update_request` في `GET provider/profile`

---

## 🌍 17. التعريب وتعدد اللغات

- [x] عربي / إنجليزي + هيدر `Lang`
- [x] `PATCH change-lang` (`preferred_locale`)
- [x] Translatable على الموديلات
- [x] إشعارات `title_ar/en` + عرض حسب `lang()`

---

## 🖥️ 18. الموقع التعريفي (Intro Site / Landing Page) — **خارج تطبيق الموبايل**

- [x] صفحة رئيسية (`IntroController@index` — sliders, services, fqs, partners, howWorks, socials)
- [x] أسئلة شائعة + شركاء + كيف يعمل + تواصل (`sendMessage`, `subscribe`)
- [x] سياسة الخصوصية + حذف حساب (صفحات Blade)
- [ ] آراء العملاء — غير موجودة في `IntroController`
- [ ] تحسين SEO لكل صفحة

---

## 📱 19. الـ API (للتطبيق المحمول) — **مطابق للتصميم (ما عدا §9 شات)**

### API العميل
- [x] Auth (register, login, OTP, forget password, reset)
- [x] Profile + change-lang + delete-account
- [x] Providers + map + favorites + categories
- [x] Bookings + additional invoice + pay
- [x] Inquiries
- [ ] Chat — **مؤجّل**
- [x] Ratings
- [x] Complaints + contact
- [x] Wallet + charge
- [x] Notifications

### API مقدم الخدمة
- [x] Auth + profile update request
- [x] Home dashboard (`GET provider/home` — Figma `design/provider/home.png`)
- [x] Bookings + additional invoice
- [x] Inquiries
- [x] Wallet + withdraw + bank-account
- [x] Notifications

### API عامة (Global)
- [x] terms, privacy, about, socials, settings, app-intro
- [x] contact/store
- [x] countries/cities (اختياري)

---

## 🔒 20. الأمان والامتثال

- [x] OTP + Sanctum + soft delete + hashed passwords + devices + `is_blocked`

---

## 📊 ملخص إجمالي

| المجال | عدد المهام التقريبي |
|--------|---------------------|
| المصادقة والمستخدمون | 18 |
| البحث والتصفية | 8 |
| الخريطة | 5 |
| الحجز | 12 |
| الاستفسارات | 6 |
| الدفع | 12 |
| الفاتورة الإضافية | 8 |
| المحفظة | 5 |
| الدردشة | 5 |
| التقييمات | 4 |
| الإشعارات | 11 |
| المواقع | 4 |
| الفئات والأنواع | 5 |
| الشكاوى | 4 |
| لوحة الإدارة | 26 |
| طلبات التحديث | 2 |
| التعريب | 4 |
| الموقع التعريفي | 9 |
| الـ API | 29 |
| الأمان | 6 |
| **الإجمالي** | **~182 مهمة** |

> **ملاحظة:** التطبيق يدعم نوعي مستخدم فقط: **العميل (Client)** و **مقدم الخدمة (Provider)**. لا يوجد منظمات أو تجار أو صيدليات.

---

> **مصادر الوثائق:**
> - `one (1).pdf` — وثيقة تغيير #1 (فلترة مقدمي الخدمة + رسالة الحجوزات الفارغة)
> - `وثيقه تغيير متطلبات (1) (1).pdf` — وثيقة تغيير متطلبات إضافية
> - `وثيقة تغيير متطلبات - ONE 2.pdf` — وثيقة تغيير #2
> - `اضافه 4.pdf` — وثيقة إضافة #4 (خريطة، استفسارات، ترتيب الحجوزات، حذف الحساب)
> - `وثيقة تغيير 5 (2).pdf` — وثيقة تغيير #5 (الدفع الإلكتروني + الفاتورة الإضافية)
> - `one3.pdf` — مقارنة النسخ
> - `التحليل (1) (1).pdf` — التحليل الفني الكامل (51 صفحة)
