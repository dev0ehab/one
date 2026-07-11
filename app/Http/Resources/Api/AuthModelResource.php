<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\ClientResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthModelResource extends JsonResource
{

  private $token = '';

  public function setToken($value)
  {
    $this->token = $value;
    return $this;
  }
  public function toArray($request)
  {
    return $this->getResource()->setToken($this->token);
  }
}
