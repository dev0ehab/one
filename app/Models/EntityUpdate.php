<?php

namespace App\Models;

use App\Enums\NotificationTypeEnum;
use App\Notifications\GlobalNotification;
use App\Jobs\SendNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;

class EntityUpdate extends BaseModel
{
	const IMAGEPATH = 'entity_updates';

	protected $fillable = [
		'updateable_type',
		'updateable_id',
		'type',
		'data',
		'is_approved',
	];

	protected $casts = [
		'data'        => 'array',
		'is_approved' => 'boolean',
	];

	public function scopewhereType($q, $model)
	{
		$q->where('updateable_type', 'App\Models\\' . $model);
	}

	/**
	 * Return the name of the folder in the views directory.
	 *
	 * @return string
	 */
	public function getFolderName(): string
	{
		return 'entity_updates';
	}

	// Morph relation
	public function updateable()
	{
		return $this->morphTo()->withTrashed()->morphWith($this->withRelations);
	}


	/**
	 * Set the data attribute.
	 * @param array $data
	 * @return void
	 */
	public function setDataAttribute($data)
	{
		$data = $this->restructureDataArray($data);
		$this->attributes['data'] = json_encode($data);
	}

	/**
	 * Get the data attribute.
	 * @return array
	 */

	public function getDataAttribute()
	{
		if (!isset($this->attributes['data']))
			return [];

		return json_decode($this->attributes['data'], true);
	}


	/**
	 * Get the relations that are sent with the update request.
	 *
	 * @return array
	 */
	public function getWithRelationsAttribute()
	{
		return [
			...data_get($this->data, 'relations', []),
			...data_get($this->data, 'custom_relations', [])
		];
	}

	/**
	 * Get the relations that are sent with the update request.
	 *
	 * @return array
	 */
	public function getRelationsAttribute()
	{
		return array_keys($this->withRelations);
	}

	/**
	 * Get the entity after setting the relations.
	 *
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function getEntityAttribute()
	{
		$model = (new $this->attributes['updateable_type'])->setRawAttributes($this->data["entity"]);

		foreach ($this->withRelations as $relation => $attributes) {
			$model->relations[$relation] = (new $attributes['class'])->setRawAttributes($attributes);
		}

		return $model;
	}

	/**
	 * Restructure the data array by saving the images for each
	 * relation of the entity.
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	private function restructureDataArray($data, ?Model $entityModel = null)
	{
		if ($entityModel === null && ($class = data_get($data, 'class'))) {
			$entityModel = $this->makeEntityModelFromClass($class);
		}

		$new_data = [];
		foreach ($data as $key => $value) {

			if (is_array($value)) {
				$value = $this->restructureDataArray($value, $entityModel);
			}

			if ($value instanceof UploadedFile && $value->isValid()) {
				if (!$entityModel) {
					throw new \InvalidArgumentException('Entity model context is required to store uploaded files.');
				}
				$modelClass = $entityModel::class;
				$value = $entityModel->uploadAllTypes($value, $modelClass::IMAGEPATH);
			}
			$new_data[$key] = $value;
		}

		return $new_data;
	}

	private function makeEntityModelFromClass(string $class): Model
	{
		$resolved = Relation::getMorphedModel($class) ?? $class;

		return new $resolved;
	}

	private function checkImageAndSave($data, $entityModel = null) {}


	/**
	 * Save the images for each relation of the entity.
	 *
	 * @param \Illuminate\Database\Eloquent\Model $entity
	 * @param array $data
	 * @param string $name
	 * @param bool $is_relation
	 *
	 * @return array
	 */
	private function saveImage($entity, $data, $name, $is_relation = false)
	{
		$entityClass = $entity->getMorphClass();

		$loop = $is_relation ? $data["relations"] : $data;

		foreach ($loop[$name] as $key => $value) {

			if ($value instanceof \Illuminate\Http\UploadedFile && $value->isValid()) {
				$value = $entity->uploadAllTypes($value, $entityClass::IMAGEPATH);
			}

			if ($is_relation) {
				$data["relations"][$name][$key] = $value;
			} else {
				$data[$name][$key] = $value;
			}
		}

		return $data;
	}


	public function getNotificationAttribute()
	{
		if (Schema::hasColumn($this->updateable->getTable(), 'is_blocked')) {
			$notifiable  = $this->updateable;
			$notify_type = $this->type == 'update' ? 'update_data' : 'delete_data';
		} else {
			$notifiable  = $this->updateable->employable ?? $this->updateable;
			$array       = explode('\\', $this->updateable->getMorphClass());
			$class       = end($array);
			$notify_type = $this->type . $class . "Data";
		}

		return [$notifiable, $notify_type];
	}

	// end of Accessors

	// boot
	public static function boot(): void
	{
		parent::boot();
		static::created(function ($model) {
			// no-op: worker entity updates removed
		});
		static::updated(function ($model) {
			if ($model->isDirty('is_approved') && $model->is_approved) {
				SendNotification::dispatch(
					$model->updateable,
					$model->updateable->getMorphClass(),
					$model->updateable->id,
					GlobalNotification::class,
					__('notification.title_entity_update_approved', [], 'ar'),
					__('notification.title_entity_update_approved', [], 'en'),
					__('notification.body_entity_update_approved', [], 'ar'),
					__('notification.body_entity_update_approved', [], 'en'),
					'entity_update_approved'
				);
			} else if ($model->isDirty('is_approved') && $model->is_approved == false) {
				SendNotification::dispatch(
					$model->updateable,
					$model->updateable->getMorphClass(),
					$model->updateable->id,
					GlobalNotification::class,
					__('notification.title_entity_update_rejected', [], 'ar'),
					__('notification.title_entity_update_rejected', [], 'en'),
					__('notification.body_entity_update_rejected', [], 'ar'),
					__('notification.body_entity_update_rejected', [], 'en'),
					'entity_update_rejected'
				);
			}
		});
	}
}
