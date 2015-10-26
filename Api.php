<?php

namespace Juuuuuu\Sncf;

use GuzzleHttp\Client;

class Api
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }
}
