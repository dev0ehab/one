<?php

namespace App\Traits;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

trait QrTrait
{

    public function generate($model, $data): void
    {
        $path = $model->getMorphClass()::IMAGEPATH;

        QrCode::format('svg')
            ->size(200)
            ->generate(json_encode($data), public_path("storage/images/$path/qr-$model->id.svg"));

    }

    public function generateQrCode($data = null): void
    {
        $path = self::IMAGEPATH;

        if ($data == null) {
            $data = [
                'wallet_id' => $this->id,
                'is_safety_mode' => $this->walletable->is_safety_mode ?? false
            ];
        }

        if (!file_exists(public_path("storage/images/$path"))) {
            mkdir(public_path("storage/images/$path"), 0777, true);
        }

        $user_type = strtolower(class_basename(auth()->user()));

        QrCode::format('svg')
            ->size(200)
            ->generate(json_encode($data), public_path("storage/images/$path/qr-$user_type-$this->id.svg"));

    }

    public function regenerateQrCode(): void
    {
        $path = self::IMAGEPATH;
        $user_type = strtolower(class_basename(auth()->user()));


        if (file_exists(public_path("storage/images/$path/qr-$user_type-$this->id.svg"))) {
            unlink(public_path("storage/images/$path/qr-$user_type-$this->id.svg"));
        }

        $this->generateQrCode();
    }

    public function getQrAttribute(): string
    {
        $path = self::IMAGEPATH;
        $user_type = strtolower(class_basename(auth()->user()));

        // check if the file exists
        if (!file_exists(asset("storage/images/$path/qr-$user_type-$this->id.svg"))) {
            $this->generateQrCode();
        }

        $file = asset("storage/images/$path/qr-$user_type-$this->id.svg");
        
        return $file;
    }
}
