<?php

namespace App\Services\Auth;

use App\Enums\LoginType;
use App\Enums\OTPThrough;
use App\Enums\OTPType;
use App\Models\Client;
use App\Models\Provider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AuthenticationService extends AuthBaseService
{
	public function __construct($model)
	{
		$this->model = $model;

		parent::__construct($model);
	}

	/**
	 * Registers a new user based on the provided data.
	 * @param $request
	 * @return array
	 */
	public function register($request): array
	{
		try {
			// Start a database transaction
			DB::beginTransaction();

			$banners = $request['banners'] ?? null;
			unset($request['banners']);

			// Create a new entity
			$entity = $this->model::create($request);

			if ($entity instanceof Provider && !empty($banners)) {
				$entity->syncBanners($banners);
			}

			if (isset($request['work_days'])) {
				$entity->workDays()->createMany($request['work_days']);
			}
			if (isset($request['categories'])) {
				$entity->categories()->sync($request['categories']);
			}

			if ($entity instanceof Client && $this->hasClientLocationData($request)) {
				$entity->locations()->create([
					'name'             => 'Main Location',
					'lat'              => $request['lat'],
					'lng'              => $request['lng'],
					'map_desc'         => $request['map_desc'],
					'country_id'       => $request['country_id'],
					'city_id'          => $request['city_id'],
					'is_main_location' => true,
				]);
			}


			$this->entity = $entity;
			// define the identifier type
			$identifierType = $this->getIdentifierType($request, true);

			$entity->sendVerificationCode($identifierType, OTPType::VERIFICATION);
			
			DB::commit();

			return [
				'key' => 'success',
				// 'msg' => isset($request['way']) && $request['way'] == LoginType::CODE ?
				// 	__('auth.registered_no_need_to_activate') : __('auth.registered'),
				'msg' => __('auth.registered'),
				'entity' => $entity,
			];
		} catch (\Exception $e) {
			DB::rollback();

			return [
				'key' => 'fail',
				'msg' => $e->getMessage(),
				'entity' => [],
			];
		}
	}

	/**
	 * Complete the user profile.
	 *
	 * @param $request
	 * @return array
	 */
	public function completeProfile($request): array
	{
		try {
			DB::beginTransaction();
			$this->entity = auth()->user();

			if ($this->entity->is_profile_completed) {
				return [
					'key' => 'profileIsCompleted',
					'msg' => __('auth.your_profile_is_completed'),
					'entity' => $this->entity->refresh(),
				];
			}

			$toComplete = [];

            if (!empty($request['trade_name'])) {
                $toComplete['trade_name'] = $request['trade_name'];
            } elseif (!empty($request['name']) && $this->entity instanceof Provider) {
                $toComplete['trade_name'] = $request['name'];
            } elseif (!empty($request['name'])) {
                $toComplete['name'] = $request['name'];
            }

			if (!empty($request['email'])) {
				$toComplete['email'] = $request['email'];
			}

			if (!empty($request['image'])) {
				$toComplete['image'] = $request['image'];
			}

			if (!empty($request['city_id'])) {
				$toComplete['city_id'] = $request['city_id'];
			}

			// Update Basic Info
			$this->entity->update(array_merge($toComplete, ['is_profile_completed' => true]));

			DB::commit();

			return [
				'key' => 'success',
				'msg' => __('auth.profile_completed'),
				'entity' => $this->entity->refresh(),
			];
		} catch (\Exception $e) {
			DB::rollback();

			return [
				'key' => 'fail',
				'msg' => $e->getMessage(),
				'entity' => [],
			];
		}
	}

	/**
	 * Resend the verification code to the user based on the provided phone and country code or email.
	 * @param $request
	 * @return array
	 */
	public function resendCode($request): array
	{
		// Find the entity
		$entity = $this->getEntity($request);

		// define the identifier type
		$identifierType = $this->getIdentifierType($request);

		// Send the verification code to the user
		$entity->sendVerificationCode($identifierType, OTPType::VERIFICATION);

		// Return the success message and the updated user data
		return [
			'key' => 'success',
			'msg' => __('auth.code_re_send'),
			'entity' => $entity->refresh(),
		];
	}

	/**
	 * Activate the user based on the provided data.
	 * @param $request
	 * @return array
	 */
	public function activate($request): array
	{
		// Find the entity
		$entity = $this->getEntity($request);

		// activate the account
		$entity->markAsActive();

		// Return the response data
		return [
			'key' => 'success',
			'msg' => __('auth.activated'),
			'entity' => $entity->refresh(),
		];
	}

	/**
	 * Login the user based on the provided data.
	 * @param $request
	 * @return array
	 */
	public function login($request): array
	{
		// Find the entity
		$entity = $this->getEntity($request);

		// define the identifier type
		$identifierType = $this->getIdentifierType($request);

		$response = [
			'key' => 'success',
			'msg' => __('auth.signed'),
			'entity' => $entity,
		];

		return $this->checkEntityStatus($response, $entity)
			// ->checkEmailActivated($response, $entity)
			->loginViaPassword($response, $request, $entity)
			->loginViaCode($response, $request, $entity, $identifierType);
	}

	/**
	 * Verify the user's phone number to login.
	 * @param $request
	 * @return array
	 */
	public function verifyLogin($request): array
	{
		// Find the entity
		$entity = $this->getEntity($request);

		// define the identifier type
		$identifierType = $this->getIdentifierType($request);

		// check if the user has authOtp
		$success = $entity->checkOtp($request->code, OTPType::LOGIN, $identifierType);

		if ($success == 'fail') {
			return ['key' => 'fail', 'msg' => __('auth.code_invalid'), 'entity' => []];
		} elseif ($success == 'expired') {
			return ['key' => 'fail', 'msg' => __('auth.code_expired'), 'entity' => []];
		}

		// Return the response data
		return [
			'key' => 'success',
			'msg' => __('auth.signed'),
			'entity' => $entity,
		];
	}

	/**
	 * Forget the user's password based on the provided data.
	 * @param $request
	 * @return array
	 */
	public function forgetPasswordSendCode($request): array
	{
		// Find the entity
		$entity = $this->getEntity($request);

		if (empty($entity)) {
			return ['key' => 'fail', 'msg' => __('auth.incorrect_key_or_phone'), 'entity' => []];
		}

		// define the identifier type
		$identifierType = $this->getIdentifierType($request);

		// Send the verification code to the user
		$entity->sendVerificationCode($identifierType, OTPType::FORGET_PASSWORD);

		return ['key' => 'success', 'msg' => __('apis.success'), 'entity' => $entity->refresh()];
	}

	/**
	 * Check the user's password reset code based on the provided data.
	 * @param $request
	 * @return array
	 */
	public function forgetPasswordCheckCode($request): array
	{
		// Find the entity
		$entity = $this->getEntity($request);

		$response = ['key' => 'fail', 'msg' => __('auth.incorrect_key_or_phone'), 'entity' => []];

		if (!empty($entity)) {
			// define the identifier type
			$identifierType = $this->getIdentifierType($request);

			// check if the user has authOtp
			$success = $entity->checkOtp($request->code, OTPType::FORGET_PASSWORD, $identifierType);

			if ($success == 'fail') {
				$response['msg'] = __('auth.code_invalid');
			} elseif ($success == 'expired') {
				$response['msg'] = __('auth.code_expired');
			} else {
				$response = ['key' => 'success', 'msg' => __('auth.code_checked'), 'entity' => $entity->refresh()];
			}
		}

		return $response;
	}

	/**
	 * Reset the user's password based on the provided data.
	 * @param $request
	 * @return array
	 */
	public function resetPassword($request): array
	{
		// Find the entity
		$entity = $this->getEntity($request);
		try {
			// Start a database transaction
			DB::beginTransaction();
			// delete the user's otp
			$entity
				->otps()
				->where(['type' => OTPType::FORGET_PASSWORD, 'code' => $request->code])
				->delete();

			// Update the user's password
			$entity->update(['password' => $request['password']]);

			// Commit the transaction
			DB::commit();
			// Return success message
			return [
				'key' => 'success',
				'msg' => __('auth.password_changed'),
			];
		} catch (\Exception $e) {
			DB::rollback();

			return [
				'key' => 'fail',
				'msg' => $e->getMessage(),
			];
		}
	}

	/**
	 * Logout the user.
	 * @return array
	 */
	public function logout(): array
	{
		// Find the entity
		// auth()->user()->currentDevice()->delete();
		auth()->user()->logout();


		// Return success message
		return [
			'key' => 'success',
			'msg' => __('auth.loggedOut'),
		];
	}

	/**
	 * Delete the user's account.
	 * @return array
	 */
	public function deleteAccount(?string $reason = null): array
	{
		$entity = auth()->user();
		try {
			DB::beginTransaction();

			if ($reason) {
				$entity->update(['delete_reason' => $reason]);
			}

			$entity->tokens()->delete();
			$entity->delete();

			// Commit the transaction
			DB::commit();

			// Return success message
			return [
				'key' => 'success',
				'msg' => __('auth.account_deleted'),
			];
		} catch (\Exception $e) {
			// Rollback the transaction in case of an error
			DB::rollback();

			// Return failure message
			return [
				'key' => 'fail',
				'msg' => $e->getMessage(),
			];
		}
	}

	protected function checkEmailActivated(&$response, $entity)
	{

		if (isset($response['key']) && $response['key'] != 'success') {
			return $this;
		}

		if (Schema::hasColumn($entity->getTable(), 'email_verified_at') && is_null($entity->email_verified_at)) {

			$entity->sendVerificationCode(OTPThrough::EMAIL, OTPType::VERIFICATION);

			$response = [
				'key' => 'emailNeedActive',
				'msg' => __('auth.email_need_activation'),
				'entity' => $entity,
			];
		}

		return $this;
	}

	protected function checkEntityStatus(&$response, $entity)
	{
		if (empty($entity)) {
			// If entity does not exist, return fail
			$response = [
				'key' => 'fail',
				'msg' => __('auth.incorrect_key_or_phone'),
				'entity' => [],
			];
		} elseif ($entity->is_blocked) {
			// If entity is blocked, return blocked
			$response = [
				'key' => 'blocked',
				'msg' => __('auth.blocked'),
				'entity' => $entity,
			];
		} elseif ($entity instanceof Provider && Schema::hasColumn($entity->getTable(), 'is_active') && !$entity->is_active) {
			$response = [
				'key' => 'needActive',
				'msg' => __('auth.not_active'),
				'entity' => $entity,
			];
		} elseif ($entity instanceof Provider && Schema::hasColumn($entity->getTable(), 'is_approved') && $entity->is_approved !== 1) {
			$response = [
				'key' => 'needApproval',
				'msg' => __('auth.not_approved'),
				'entity' => $entity,
			];
		}
		elseif (Schema::hasColumn($entity->getTable(), 'is_completed') && !$entity->is_completed) {
			$response = [
				'key' => 'profileNotCompleted',
				'msg' => __('auth.profile_not_completed'),
				'entity' => $entity,
			];
		}

		return $this;
	}
	protected function checkProfileCompleted(&$response, $entity)
	{
		if (Schema::hasColumn($entity->getTable(), 'is_completed') && !$entity->is_completed) {
			// If entity is not completed, return profile not completed
			$response = [
				'key' => 'profileNotCompleted',
				'msg' => __('auth.profile_not_completed'),
				'entity' => $entity,
			];
		}

		return $this;
	}

	protected function loginViaPassword(&$response, $request, $entity)
	{
		if (isset($response['key']) && $response['key'] != 'success') {
			return $this;
		}

		$way = isset($request['way']) && ($request['way'] == LoginType::NORMAL);

		if ($way && !Hash::check($request['password'], $entity->password)) {
			// If password is incorrect, return failure
			$response = [
				'key' => 'fail',
				'msg' => __('auth.incorrect_pass'),
				'entity' => [],
			];
		}

		return $this;
	}

	protected function hasClientLocationData($request): bool
	{
		return !empty($request['lat'])
			&& !empty($request['lng'])
			&& !empty($request['map_desc'])
			&& !empty($request['country_id'])
			&& !empty($request['city_id']);
	}

	protected function loginViaCode($response, $request, $entity, $identifierType)
	{
		if (isset($response['key']) && $response['key'] != 'success') {
			return $response;
		}

		$way = isset($request['way']) && ($request['way'] == LoginType::CODE);

		if (!$entity->is_active && $way) {
			// If entity is not active, return not active
			$entity->sendVerificationCode($identifierType, OTPType::VERIFICATION);
			$response = [
				'key' => 'needActive',
				'msg' => __('auth.not_active'),
				'entity' => $entity,
			];
		} elseif ($entity->is_active && $way) {
			// If all checks pass, return verification code
			$entity->sendVerificationCode($identifierType, OTPType::LOGIN);
			$response = [
				'key' => 'needVerification',
				'msg' => __('auth.phone_code_sent'),
				'entity' => $entity,
			];
		}

		return $response;
	}
}
