<?php

namespace Database\Seeders;

use App\Enums\ComplainStatusEnum;
use App\Models\Client;
use App\Models\Complain;
use App\Models\Provider;
use Illuminate\Database\Seeder;

class ComplainSeeder extends Seeder
{
    public function run(): void
    {
        $clients = Client::limit(3)->get();
        $provider = Provider::first();

        if ($clients->isNotEmpty()) {
            Complain::create([
                'complainable_id'   => $clients[0]->id,
                'complainable_type' => Client::class,
                'status'            => ComplainStatusEnum::NEW,
                'title'             => 'تأخر الموعد',
                'description'       => 'تم تأخير الموعد أكثر من 30 دقيقة دون إشعار.',
            ]);

            Complain::create([
                'complainable_id'   => $clients[0]->id,
                'complainable_type' => Client::class,
                'status'            => ComplainStatusEnum::PENDING,
                'title'             => 'مشكلة في الفاتورة',
                'description'       => 'المبلغ المعروض لا يطابق ما تم الاتفاق عليه.',
            ]);

            if ($clients->count() > 1) {
                Complain::create([
                    'complainable_id'   => $clients[1]->id,
                    'complainable_type' => Client::class,
                    'status'            => ComplainStatusEnum::FINISH,
                    'title'             => 'جودة الخدمة',
                    'description'       => 'الخدمة كانت جيدة لكن الانتظار طويل.',
                    'reply'             => 'شكراً لتواصلكم. تمت مراجعة الملاحظة مع مقدم الخدمة.',
                ]);
            }
        }

        if ($provider) {
            Complain::create([
                'complainable_id'   => $provider->id,
                'complainable_type' => Provider::class,
                'status'            => ComplainStatusEnum::NEW,
                'title'             => 'مشكلة في الاشتراك',
                'description'       => 'لم يتم تفعيل خدمة الحجوزات بعد الدفع.',
            ]);
        }
    }
}
