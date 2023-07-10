<?php

namespace MichiServices\Common\Services;

use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class ApiService
{
    protected string $endpoint;

    public function request($method, $path, $data = [], $withALLHeaders = false)
    {
        try {

            $response = $this->getRequest($method, $path, $data, $withALLHeaders);
            if ($response->successful()) {
                return $response->json();
            }
            throw new HttpException($response->status(), $response->body());
        } catch (\Exception $e) {

            if (method_exists($e, 'getStatusCode')) {
                $statusCode = $e->getStatusCode();
            } else {
                $statusCode = 500;
            }

            throw new HttpException($statusCode, $e->getMessage());
        }
    }

    public function getRequest($method, $path, $data = [], $withALLHeaders = false)
    {

        $token = '';
        try {
            $decoded = decrypt(request()->cookie('jwt'), false);
            list($userId, $token) = explode('|', $decoded);
        } catch (\Exception) {
            $token = request()->cookie('jwt');
        }

        $payload = [
            'Authorization' => 'Bearer ' . $token
        ];
        if ($withALLHeaders) {
            $headers = request()->header();
            $payload = [
                ...$headers,
                'Authorization' => 'Bearer ' . $token
            ];
        }
        return \Http::acceptJson()->withHeaders($payload)->$method("{$this->endpoint}{$path}", $data);
    }

    public function post($path, $data, $withALLHeaders = false)
    {
        return $this->request('post', $path, $data, $withALLHeaders);
    }

    public function get($path, $data = [], $withALLHeaders = false)
    {
        return $this->request('get', $path, $data, $withALLHeaders);
    }

    public function put($path, $data, $withALLHeaders = false)
    {
        return $this->request('put', $path, $data, $withALLHeaders);
    }

    public function delete($path, $withALLHeaders = false)
    {
        return $this->request('delete', $path, [], $withALLHeaders);
    }
}
