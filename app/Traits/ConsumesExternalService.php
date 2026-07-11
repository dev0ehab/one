<?php

namespace App\Traits;

use GuzzleHttp\Client;

trait ConsumesExternalService
{
    /**
     * send a request to any service
     *
     * @param $method string
     * @param $requestUrl string
     * @param $queryParams array
     * @param $formParams array
     * @param $headers array
     * @param $isJsonRequest bool
     * @param $hasFiles bool
     * @return stdClass|string
     */

    public function makeRequest(string $method, string $requestUrl, array $queryParams = [], array $formParams = [], array $headers = [], bool $isJsonRequest = false, bool $hasFiles = false)
    {
        $client = new Client([
            'base_uri' => $this->base_url
        ]);

        if(method_exists($this,'resolveAuthorization')){
            $this->resolveAuthorization($queryParams, $formParams, $headers);
        }

        $response = $client->request($method,$this->generateApiEndPoint($requestUrl),$this->buildRequestPayload($headers,$queryParams,$formParams,$isJsonRequest));

        $response = $response->getBody()->getContents();


        if(method_exists($this,'decodeResponse')) {
            $response = $this->decodeResponse($response);
        }

        if(method_exists($this,'checkIfErrorResponse')) {
            $this->checkIfErrorResponse($response);
        }
        return $response;

    }

    private function generateApiEndPoint(& $requestUrl)
    {
        return $this->apiVersion ?  $this->apiVersion . '/' . $requestUrl : $requestUrl;
    }

    private function buildRequestPayload($headers,$queryParams,$formParams,$isJsonRequest): array
    {
        $payLoad = [];
        $payLoad['headers'] = $headers;
        $payLoad['query'] = $queryParams;
        if($isJsonRequest) {
            $payLoad['body'] = json_encode($formParams);
            $payLoad[] = 'json';
        }else {
            $payLoad['form_params'] = $formParams;
        }
        return $payLoad;
    }
}