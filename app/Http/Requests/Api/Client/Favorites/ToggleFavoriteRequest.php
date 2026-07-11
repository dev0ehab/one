<?php

namespace App\Http\Requests\Api\Client\Favorites;

use App\Http\Requests\BaseRequest;

class ToggleFavoriteRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'provider_id' => 'required|exists:providers,id,deleted_at,NULL',
        ];
    }
}
