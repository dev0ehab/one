<?php

namespace App\Http\Requests\Api\Provider\Auth;

use App\Enums\LoginType;
use App\Http\Requests\BaseRequest;
use App\Overrides\CustomPasswordRule;
use Illuminate\Support\Arr;

/**
 * POST /api/provider/register
 *
 * {
 *   "owner_name": "محمد أحمد",
 *   "trade_name": "مؤسسة النور للتجارة",
 *   "country_code": "966",
 *   "phone": "512345679",
 *   ...
 * }
 */
class RegisterRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'owner_name'              => 'required|string|min:3|max:50',
            'trade_name'              => 'required|string|min:3|max:50',
            'country_code'            => 'required|numeric|digits_between:2,5',
            'phone'                   => 'required|numeric|digits_between:9,10|unique:providers,phone,null,id,deleted_at,NULL|starts_with:5',
            'password'                => ['required', 'string', 'confirmed', CustomPasswordRule::min(8)->letters()->numbers()],
            'national_id'             => 'required|numeric|digits:10',
            'category_id'             => 'required|exists:categories,id',
            'commercial_register_num' => 'required|numeric|digits_between:10,16',
            'lat'                     => 'required|numeric|between:-90,90',
            'lng'                     => 'required|numeric|between:-180,180',
            'map_desc'                => 'required|string|max:191',
            'start_time'              => 'required|date_format:H:i',
            'end_time'                => 'required|date_format:H:i|after:start_time',
            'banners'                 => 'required|array|min:1',
            'banners.*'               => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'accept_terms'            => 'required|accepted',
        ];
    }

    public function messages(): array
    {
        return [
            'end_time.after' => __('admin.end_time_must_be_after_start_time'),
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'way'          => LoginType::CODE,
            'phone'        => fixPhone($this->phone),
            'country_code' => fixPhone($this->country_code ?? '966'),
            'is_active'    => false,
        ]);
    }

    public function validated($key = null, $default = null)
    {
        $data = Arr::except(parent::validated($key, $default), [
            'password_confirmation',
            'accept_terms',
            'banners',
        ]);

        if (empty($data['email'])) {
            $data['email'] = $data['country_code'] . $data['phone'] . '@provider.one';
        }

        return $data;
    }
}
