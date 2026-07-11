<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseTrait
{

  /**
   * keys : success, fail, needActive, waitingApprove, unauthenticated, blocked, exception
   */
  public function response($key, $msg, $data = [], $anotherKey = []): JsonResponse
  {
    $response = [
      'key' => (string) $key,
      'msg' => (string) $msg,
    ];

    $this->addNotificationCount($response, $key);
    $this->addAdditionalData($response, $anotherKey);
    $this->addResponseData($response, $key, $data);

    return response()->json($response);
  }

  /**
   * Add notification count to the response.
   */
  private function addNotificationCount(&$response, $key): void
  {
    if ($key === 'success' && request()->has('count_notifications')) {
      $count = auth()->check() ? auth()->user()->notifications()->unread()->count() : 0;
      $response['count_notifications'] = $count;
    }
  }

  /**
   * Add additional data to the response.
   */
  private function addAdditionalData(&$response, $anotherKey): void
  {
    foreach ($anotherKey as $otherKey => $value) {
      $response[$otherKey] = $value;
    }
  }

  /**
   * Add response data to the response.
   */
  private function addResponseData(&$response, $key, $data): void
  {
    if (!empty($data) && in_array($key, ['success', 'needActive', 'exception', 'needVerification', 'profileNotCompleted'])) {
      $response['data'] = $data;
    }
  }


  /**
   * Return the response for unauthenticated user.
   * @return JsonResponse
   */
  public function unauthenticatedReturn(): JsonResponse
  {
    return $this->response('unauthenticated', trans('auth.unauthenticated'));
  }

  /**
   * Return the response for unauthorized user.
   * @return JsonResponse
   */
  public function unauthorizedReturn($otherData): JsonResponse
  {
    return $this->response('unauthorized', trans('auth.not_authorized'), [], $otherData);
  }

  /**
   * Return the response for blocked user.
   * @return JsonResponse
   */
  public function blockedReturn($user, $msg): JsonResponse
  {
    $user->logout();
    return $this->response('blocked', $msg);
  }

  /**
   * Return the response for user that need activation.
   * @return JsonResponse
   */
  public function phoneActivationReturn($data, $msg): JsonResponse
  {
    return $this->response('needActive', $msg, $data);
  }

  /**
   * Return the response for user that need activation.
   * @return JsonResponse
   */
  public function AdminApprovalReturn($data, $msg): JsonResponse
  {
    return $this->response('needApproval', $msg, $data);
  }

  /**
   * Return the response for user that need activation.
   * @return JsonResponse
   */
  public function profileNotCompletedReturn($data, $msg): JsonResponse
  {
    return $this->response('profileNotCompleted', $msg, $data);
  }


  /**
   * Return the response for user that need verification.
   * @return JsonResponse
   */
  public function verificationCodeReturn($data, $msg): JsonResponse
  {
    return $this->response('needVerification', $msg, $data);
  }



  /**
   * Return fail response.
   * @return JsonResponse
   */
  public function failMsg($msg): JsonResponse
  {
    return $this->response('fail', $msg);
  }

  /**
   * Return success response.
   * @return JsonResponse
   */
  public function successMsg($msg = null): JsonResponse
  {
    return $this->response('success', $msg ?? trans('apis.success'));
  }

  /**
   * Return success response with data.
   * @return JsonResponse
   */
  public function successData($data): JsonResponse
  {
    return $this->response('success', trans('apis.success'), $data);
  }

  /**
   * Return success response with other data.
   * @return JsonResponse
   */
  public function successOtherData(array $dataArr): JsonResponse
  {
    return $this->response('success', trans('apis.success'), [], $dataArr);
  }


  /**
   * Return the response for exception.
   * @return JsonResponse
   */
  public function getCode($key): int
  {
    switch ($key) {
      case 'success':
        $code = 200;
        break;
      case 'fail':
        $code = 400;
        break;
      case 'needActive':
        $code = 203;
        break;
      case 'unauthorized':
        $code = 400;
        break;
      case 'unauthenticated':
        $code = 401;
        break;
      case 'blocked':
        $code = 423;
        break;
      case 'exception':
        $code = 500;
        break;

      default:
        $code = 200;
        break;
    }

    return $code;
  }
}
