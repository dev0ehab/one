<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Schema;

use App\Http\Requests\BaseRequest;

class UpdateBaseRequest extends BaseRequest
{

    protected $data;
    protected $entityModel;
    protected $entityClass; // Class
    protected $entityIdKey; // the key conains the entity id


    protected $mainFields = [
        // 'field'
    ];

    protected $relations = [
        // 'relation' => ['field1', 'field2']
    ];

    protected $customRelations = [
        // "relation" => "callback function"
    ];

    /** Never persist these when building entity update payloads (password would be re-hashed). */
    protected static array $protectedEntityAttributes = [
        'password',
        'remember_token',
    ];

    public static function stripProtectedEntityAttributes(array $data): array
    {
        return array_diff_key($data, array_flip(static::$protectedEntityAttributes));
    }


    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {

            $customRelationKeys = array_filter(
                array_keys($this->customRelations),
                fn ($key) => is_string($key) && !is_numeric($key)
            );
            $with = array_merge($customRelationKeys, array_keys($this->relations));

            $this->entityModel = $this->entityClass::with($with)->find($this->{$this->entityIdKey});

            if (!$this->entityModel) {
                throw new HttpResponseException(response()->json([
                    'key' => 'fail',
                    'msg' => __('validation.entity_model_not_found'),
                ], 400));
            }

            if ($this->entityModel->entityUpdateRequest()->where('is_approved', null)->exists()) {
                throw new HttpResponseException(response()->json([
                    'key' => 'fail',
                    'msg' => __('validation.already_has_update_request'),
                ], 400));
            }

            if ($this->entityModel->is_approved === null) {
                throw new HttpResponseException(response()->json([
                    'key' => 'fail',
                    'msg' => __('validation.entity_not_approved'),
                ], 400));
            }

            request()->merge([
                'entityModel' => $this->entityModel
            ]);

            $this->setData($this->mainFields, $this->entityModel, "entity");

            $entityModelRelations = $this->entityModel->getRelations();

            foreach ($this->relations as $relation => $fields) {

                if (is_array($fields)) {
                    $this->setRelationData($fields, $entityModelRelations[$relation], $relation);
                }
            }

            foreach (array_values($this->customRelations) as $callback) {
                $this->{$callback}();
            }

            $this->merge([
                'data' => $this->data,
                'entityModel' => $this->entityModel,
            ]);
        });
    }


    public function prepareForValidation()
    {
        $this->merge([
            $this->entityIdKey => request($this->entityIdKey, auth()->id()),
        ]);
    }


    private function setData($fields, $model, $name, $is_realtion = false)
    {
        $is_updated = false;
        $data = $model->getAttributes();
        $data['class'] = $model->getMorphClass();
        foreach ($fields as $attribute) {
            if ($this->has($attribute) && $this->$attribute !== Null && $this->{$attribute} != $model->{$attribute}) {
                $is_updated = true;
                $data[$attribute] = $this->{$attribute};
            }
        }

        $data = static::stripProtectedEntityAttributes($data);

        if ($is_realtion) {
            return $this->data["relations"][$name] = $data;
        }

        return $this->data[$name] = $data;
    }


    private function setRelationData($fields, $model, $name)
    {
        $this->setData($fields, $model, $name, true);
    }
}
