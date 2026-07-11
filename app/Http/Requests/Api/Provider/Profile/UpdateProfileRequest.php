<?php

namespace App\Http\Requests\Api\Provider\Profile;

use App\Http\Requests\Api\UpdateBaseRequest;
use App\Models\Provider;

/**
 * Provider edit profile: owner_name, trade_name, category_id, commercial_register_num,
 * start_time, end_time, banners.
 */
class UpdateProfileRequest extends UpdateBaseRequest
{
    protected $data;
    protected $entityModel;
    protected $entityClass = Provider::class;
    protected $entityIdKey = 'id';

    protected $mainFields = [
        'owner_name',
        'trade_name',
        'category_id',
        'commercial_register_num',
        'start_time',
        'end_time',
    ];

    /** Callback-only (numeric key); banners are not an Eloquent relation. */
    protected $customRelations = [
        'captureBannersUpdate',
    ];

    public function rules(): array
    {
        return [
            'owner_name'              => 'sometimes|string|max:50',
            'trade_name'              => 'sometimes|string|max:50',
            'category_id'             => 'sometimes|exists:categories,id',
            'commercial_register_num' => 'sometimes|numeric|digits_between:10,16',
            'start_time'              => 'sometimes|date_format:H:i',
            'end_time'                => 'sometimes|date_format:H:i|after:start_time',
            'banners'                 => 'sometimes|array|min:1',
            'banners.*'               => 'sometimes|image|mimes:jpeg,png,jpg|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'end_time.after' => __('admin.end_time_must_be_after_start_time'),
        ];
    }

    protected function captureBannersUpdate(): void
    {
        if (!$this->hasFile('banners')) {
            return;
        }

        if (!isset($this->data['entity'])) {
            $this->data['entity'] = static::stripProtectedEntityAttributes($this->entityModel->getAttributes());
            $this->data['entity']['class'] = $this->entityModel->getMorphClass();
        }

        $this->data['entity']['banners'] = $this->file('banners');
    }
}
