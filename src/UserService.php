<?php

namespace MichiServices\Common;

class UserService extends ApiService
{
    public function __construct()
    {
        $this->endpoint = config('microservices.users') . '/api';
    }
}
