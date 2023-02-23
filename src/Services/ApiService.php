<?php

namespace MichiServices\Common\Services;

use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class ApiService
{
    protected string $endpoint;

    public function request($method, $path, $data = [])
    {
        try {

            $response = $this->getRequest($method, $path, $data);
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

    public function getRequest($method, $path, $data = [])
    {
        return \Http::acceptJson()->withHeaders([
            'Authorization' => 'Bearer ' . request()->cookie('jwt')
        ])->$method("{$this->endpoint}{$path}", $data);
    }

    public function post($path, $data)
    {
        return $this->request('post', $path, $data);
    }

    public function get($path)
    {
        return $this->request('get', $path);
    }

    public function put($path, $data)
    {
        return $this->request('put', $path, $data);
    }

    public function delete($path)
    {
        return $this->request('delete', $path);
    }
}
