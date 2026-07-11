<?php

namespace Database\Seeders;

use App\Traits\ImageFakerTrait;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagesTableSeeder extends Seeder
{
    use ImageFakerTrait;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createImageWithStore("App\Models\Page", "images/fakers", count($this->data()));
        DB::table('pages')->insert($this->data());
    }

    private function data()
    {
        return [
            [
                'image' => '1.png',
                'slug' => 'about',
                'title' => json_encode(['ar' => 'من نحن', 'en' => 'About us'], JSON_UNESCAPED_UNICODE),
                'content' => json_encode(['ar' => '<p>تكر يهدف إلى تلبية احتياجات المستخدمين في [وصف المجال الذي يستهدفه التطبيق]. يوفر التطبيق مجموعة من الميزات والخدمات المفيدة التي تساعد المستخدمين على القيام بمهامهم بكل سهولة وفاعلية.</p></p>',
                 'en' => '<p>Tucker aims to meet the needs of users in [Description of the field the application targets]. The application provides a set of features and services that help users carry out their tasks easily and effectively.</p>'], JSON_UNESCAPED_UNICODE),
            ],

            [
                'image' => '2.png',
                'slug' => 'terms',
                'title' => json_encode(['ar' => 'الشروط والاحكام', 'en' => 'Terms and Conditions'], JSON_UNESCAPED_UNICODE),
                'content' => json_encode([
                    'ar' => 'شروط وأحكام الاستخدام

                شكرًا لاستخدامك لتطبيق [اسم التطبيق]. يرجى قراءة هذه الشروط والأحكام بعناية قبل استخدام التطبيق. يعتبر استخدامك للتطبيق تأكيدًا منك على قبول هذه الشروط والأحكام والالتزام بها بشكل كامل. إذا كنت لا توافق على هذه الشروط والأحكام، يرجى عدم استخدام التطبيق.
                
                الملكية الفكرية: 
                أنت توافق على أن جميع حقوق الملكية الفكرية المتعلقة بتطبيق [اسم التطبيق] ومحتواه (بما في ذلك العلامات التجارية، والشعارات، والنصوص، والصور، والرسومات، والأيقونات، والبرمجيات، والمحتوى الصوتي والبصري الآخر) تعود إلى المطور أو الجهة المملوكة لهذه الحقوق. يُحظر نسخ، توزيع، نقل، عرض عام، بيع، أو استخدام أي من هذه العناصر بأي طريقة تنتهك حقوق الملكية الفكرية للمطور.
                
                الاستخدام الشخصي: 
                يتم توفير التطبيق للاستخدام الشخصي فقط. يجب عليك استخدام التطبيق وفقًا للقوانين واللوائح المعمول بها ولأغراض قانونية فقط. يُحظر استخدام التطبيق بطرق تنتهك حقوق الملكية الفكرية للآخرين أو تسبب ضررًا للمطور أو أي طرف آخر.
                
                المحتوى المستخدم: 
                عند استخدام التطبيق، قد يطلب منك تقديم بعض المعلومات الشخصية. يجب عليك التأكد من أن المعلومات التي تقدمها صحيحة ومحدّثة. يحظر استخدام معلومات المستخدمين الآخرين بأي طريقة تنتهك خصوصيتهم أو حقوقهم.
                
                التحديثات والتعديلات: 
                قد يتم تحديث التطبيق وإجراء تعديلات عليه من وقت لآخر. يجوز للمطور تحديث الشروط والأحكام دون إشعار مسبق. ينبغي عليك مراجعة هذه الشروط والأحكام بشكل منتظم للتأكد من التزامك بها الحالية.
                
                الضمان والمسؤولية                                                                                                                                        : 
                يقدم   التطبيق "كما هو" دون أي ضمانات صريحة أو ضمنية. يُحظر على المطور أو أي طرف آخر يشارك في إنشاء أو توفير التطبيق أن يكونواستمرارًا في الشروط والأحكام: 
                
                ويُحظر على المطور أو أي طرف آخر يشارك في إنشاء أو توفير التطبيق أن يكونوا مسؤولين عن أي ضرر مباشر أو غير مباشر ينتج عن استخدامك للتطبيق أو عدم قدرتك على استخدامه، بما في ذلك ولكن لا يقتصر على الأضرار المادية وفقدان البيانات والانقطاع عن الخدمة.
                
                ربط المواقع الخارجية: 
                قد يحتوي التطبيق على روابط إلى مواقع الويب الخارجية التي لا تخضع للسيطرة المطور. لا يتحمل المطور مسؤولية المحتوى أو الأداء أو الأمان أو سياسات الخصوصية لتلك المواقع الخارجية. ينبغي عليك أخذ الحيطة والحذر عند الانتقال إلى مواقع الويب الخارجية عن طريق الروابط المقدمة في التطبيق.
                
                إنهاء الاستخدام: 
                يحتفظ المطور بحق إنهاء استخدامك للتطبيق في أي وقت وبأي سبب دون إشعار مسبق. قد يتضمن ذلك إنهاء الوصول إلى حسابك وحذف بياناتك المخزنة في التطبيق.
                
                التعديلات على الشروط والأحكام: 
                يحتفظ المطور بحق تعديل هذه الشروط والأحكام في أي وقت. ينبغي عليك مراجعة هذه الشروط والأحكام بشكل دوري للتأكد من التزامك بها الحالية.
                
                الاتصال: 
                إذا كان لديك أي أسئلة أو استفسارات حول الشروط والأحكام، يرجى الاتصال بنا عبر [ذكر وسيلة الاتصال مثل البريد الإلكتروني أو الهاتف].
                
                يرجى ملاحظة أن هذه الشروط والأحكام تعتبر نسخة مترجمة للمساعدة في توفير المعلومات باللغة العربية. ومع ذلك، إذا وجد تباين بين النسخة العربية والنسخة الإنجليزية، يعتبر النص الإنجليزي الأصلي لهذه الشروط والأحكام هو النص الساري والملزم.',
                    'en' => 'Terms and conditions of use

                 Thank you for using [App Name]. Please read these terms and conditions carefully before using the application. Your use of the application constitutes your confirmation that you fully accept and abide by these terms and conditions. If you do not agree to these terms and conditions, please do not use the application.
                 
                 intellectual property: 
                 You agree that all intellectual property rights relating to the [App Name] application and its content (including trademarks, logos, text, images, graphics, icons, software, and other audio-visual content) belong to the developer or party that owns such rights. Copying, distributing, transmitting, publicly displaying, selling, or using any of these items in any way that violates the developers intellectual property rights is prohibited.
                 
                 Personal use: 
                 The application is provided for personal use only. You must use the Application in accordance with applicable laws and regulations and for lawful purposes only. Use of the Application in ways that infringe the intellectual property rights of others or cause harm to the Developer or any other party is prohibited.
                 
                 Content used: 
                 When using the Application, you may be asked to provide certain personal information. You must ensure that the information you provide is correct and up to date. It is prohibited to use other users information in any way that violates their privacy or rights.
                 
                 Updates and modifications: 
                 The Application may be updated and made modifications from time to time. The Developer may update the Terms and Conditions without prior notice. You should review these terms and conditions regularly to ensure that you comply with them.
                 
                 Warranty and liability                                                                                                                                                                                                          : 
                 The      application is provided as is without any express or implied warranties. The Developer or any other party involved in creating or providing the Application is prohibited from beingContinuing the Terms and Conditions: 
                 
                 The developer or any other party involved in creating or providing the application is prohibited from being liable for any direct or indirect damage resulting from your use of the application or your inability to use it, including but not limited to physical damage, loss of data, and interruption of service.
                 
                 Linking to external sites: 
                 The application may contain links to external websites that are not under the control of the developer. The developer is not responsible for the content, performance, security or privacy policies of those external sites. You should exercise caution when navigating to external websites via links provided in the Application.
                 
                 Termination of use: 
                 The developer reserves the right to terminate your use of the application at any time and for any reason without prior notice. This may include terminating access to your account and deleting your data stored in the app.
                 
                 Amendments to terms and conditions: 
                 The developer reserves the right to modify these terms and conditions at any time. You should review these terms and conditions periodically to ensure your compliance with them.
                 
                 Connection: 
                 If you have any questions or inquiries about the Terms and Conditions, please contact us via [state contact method such as email or telephone].
                 
                 Please note that these terms and conditions are a translated version to assist in providing information in Arabic. However, if there is a discrepancy between the Arabic version and the English version, the original English text of these terms and conditions is considered the valid and binding text.'
                ], JSON_UNESCAPED_UNICODE),
            ],
            [
                'image' => '3.png',
                'slug' => 'privacy',
                'title' => json_encode([
                    'ar' => 'سياسة الخصوصية',
                    'en' => 'privacy policy',
                ], JSON_UNESCAPED_UNICODE),
                'content' => json_encode([
                    'ar' => 'نحن في [اسم الشركة/التطبيق] نولي أهمية كبيرة لحماية خصوصية المستخدمين لدينا. تحترم هذه السياسة خصوصيتك وتشرح كيفية جمعنا واستخدامنا وحمايتنا للمعلومات التي تقدمها عند استخدام التطبيق. يرجى قراءة هذه السياسة بعناية لفهم ممارساتنا فيما يتعلق بالبيانات الشخصية وكيفية معالجتها. باستخدام التطبيق، فإنك توافق على جمع واستخدام وكشف المعلومات الشخصية وفقًا لهذه السياسة.

                المعلومات التي نجمعها: 
                عند استخدام التطبيق، قد نقوم بجمع بعض المعلومات الشخصية التي تقدمها بشكل طوعي، مثل الاسم وعنوان البريد الإلكتروني ومعلومات الاتصال الأخرى. قد نقوم أيضًا بتسجيل معلومات غير شخصية، مثل نوع المتصفح ونظام التشغيل وتفاصيل الجهاز، بهدف تحسين خدمتنا وتجربتك.
                
                استخدام المعلومات: 
                نستخدم المعلومات التي نجمعها لتخصيص وتحسين تجربتك في التطبيق، وتوفير المحتوى والخدمات المناسبة لك، والتواصل معك بشأن التحديثات والمستجدات، وتحسين خدماتنا وتطويرها، وتوفير الدعم الفني عند الحاجة.
                
                حماية المعلومات: 
                نحمي المعلومات الشخصية التي نجمعها من خلال إجراءات أمنية ملائمة للحفاظ على سرية وسلامة البيانات. نحن نتبع معايير الصناعة المعترف بها لحماية المعلومات، ولكن يجب ملاحظة أنه لا يوجد نظام أمان مطلق. لذا، فإننا لا نضمن بشكل صريح أو ضمني سرية المعلومات المرسلة إلينا.
                
                مشاركة المعلومات: 
                نحن لا نبيع أو نؤجر أو نتاجر بأي طريقة معلوماتك الشخصية لأطراف ثالثة. قد نقوم بمشاركة بعض المعلومات مع جهات خارجية معتمدة عند الحاجة، مثل مقدمي الخدمات الفنية والشركاء التجاريين، ولكن ذلك يتم وفقًا للقوانين السارية وبما يتوافق مع هذه السياسة.
                
                روابط إلى مواقع خارجية: 
                قد يحتستمر السياسة في شرح العديد من النقاط الأخرى المتعلقة بالمعلومات الشخصية والتعامل معها، مثل استخدام ملفات تعريف الارتباط (الكوكيز)، وحقوق الدخول والتصحيح والحذف للمعلومات الشخصية، وحماية الأطفال عبر الإنترنت، وتغييرات في سياسة الخصوصية، والاتصال بنا لأية استفسارات أو مخاوف تتعلق بالخصوصية.
                
                يرجى ملاحظة أن هذه النسخة الملخصة لسياسة الخصوصية وتهدف إلى توفير نظرة عامة فقط. إذا كنت ترغب في مزيد من التفاصيل والمعلومات، يرجى الرجوع إلى النسخة الكاملة لسياسة الخصوصية المتاحة في التطبيق أو على موقعنا الإلكتروني.
                
                نحن نتعهد بالامتثال لمبادئ حماية الخصوصية وتنظيمات البيانات ذات الصلة في البلدان التي نعمل بها. نحرص على الحفاظ على سرية وأمان المعلومات الشخصية التي تثق بنا إياها.
                
                تأكيدًا للتزامنا بحماية الخصوصية، فإن استخدامك المستمر للتطبيق يعتبر موافقة منك على سياسة الخصوصية هذه. إذا كنت لا توافق على أي جزء من هذه السياسة، فيرجى التوقف عن استخدام التطبيق وحذفه من جهازك.
                
                تاريخ السريان: [تحديد تاريخ السريان لسياسة الخصوصية]
                
                ',
                    'en' => 'We at [Company/App Name] attach great importance to protecting the privacy of our users. This policy respects your privacy and explains how we collect, use and protect the information you provide when you use the App. Please read this policy carefully to understand our practices regarding personal data and how we process it. By using the Application, you consent to the collection, use and disclosure of personal information in accordance with this Policy.

                Information we collect: 
                When you use the Application, we may collect certain personal information that you voluntarily provide, such as name, email address, and other contact information. We may also log non-personal information, such as browser type, operating system and device details, in order to improve our service and your experience.
                
                Use of information: 
                We use the information we collect to personalize and improve your experience in the application, provide appropriate content and services to you, communicate with you about updates and developments, improve and develop our services, and provide technical support when needed.
                
                Information protection: 
                We protect the personal information we collect with appropriate security measures to maintain the confidentiality and integrity of the data. We follow recognized industry standards to protect information, but you should note that no security system is absolute. Therefore, we do not explicitly or implicitly guarantee the confidentiality of the information transmitted to us.
                
                Sharing information: 
                We do not sell, rent, or otherwise trade your personal information to third parties. We may share some information with approved third parties when needed, such as technical service providers and business organizations, but this is done in accordance with applicable laws and in accordance with this policy.
                
                Links to external sites: 
                The policy may go on to explain many other points relating to personal information and its handling, such as the use of cookies, rights of access, correction and deletion of personal information, protection of children online, changes to the privacy policy, and contacting us for any privacy inquiries or concerns.
                
                Please note that this summary version of the Privacy Policy is intended to provide an overview only. If you would like more details and information, please refer to the full version of the Privacy Policy available in the application or on our website.
                
                We undertake to comply with the Privacy Shield Principles and relevant data regulations in the countries in which we operate. We take care to maintain the confidentiality and security of the personal information you trust with us.
                
                In confirmation of our commitment to protecting privacy, your continued use of the application constitutes your acceptance of this privacy policy. If you do not agree with any part of this policy, please stop using the application and delete it from your device.
                
                Effective Date: [Specify the effective date for the Privacy Policy]'
                ], JSON_UNESCAPED_UNICODE),
            ],


            [
                'image' => '4.png',
                'slug' => 'who_is_us',
                'title' => json_encode(['ar' => 'من نكون نحن', 'en' => ' who are we'], JSON_UNESCAPED_UNICODE),
                'content' => json_encode(['ar' => 'وصف من نكون نحن', 'en' => '  who are we content '], JSON_UNESCAPED_UNICODE),
            ],


            [
                'image' => '5.png',
                'slug' => 'our_vision',
                'title' => json_encode(['ar' => 'رؤيتنا', 'en' => ' our vision'], JSON_UNESCAPED_UNICODE),
                'content' => json_encode(['ar' => '<p>وصف رؤيتنا</p>', 'en' => '<p>ou vision content</p>'], JSON_UNESCAPED_UNICODE),
            ],
            [
                'image' => '6.png',
                'slug' => 'our_goal',
                'title' => json_encode(['ar' => 'هدفنا', 'en' => ' our goal'], JSON_UNESCAPED_UNICODE),
                'content' => json_encode(['ar' => '<p>وصف هدفنا</p>', 'en' => '<p>our goal content</p>'], JSON_UNESCAPED_UNICODE),
            ],
            [
                'image' => '6.png',
                'slug' => 'our_meassage',
                'title' => json_encode(['ar' => 'رسالتنا', 'en' => ' our meassage'], JSON_UNESCAPED_UNICODE),
                'content' => json_encode(['ar' => '<p>وصف رسالتنا</p>', 'en' => '<p>our meassage content</p>'], JSON_UNESCAPED_UNICODE),
            ],
        ];
    }
}
