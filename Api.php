<?php

namespace Juuuuuu\Sncf;

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class Api
{
    protected $client;
    private $codeUIC;
    private $username;
    private $password;

    public function __construct($codeUIC, $username, $password)
    {
        $this->codeUIC = $codeUIC;
        $this->username = $username;
        $this->password = $password;

        $this->client = new Client();
        $response = $this->client->request('GET', 'http://api.transilien.com/gare/'.$this->codeUIC.'/depart/', [
            'auth' => [$this->username, $this->password],
            'headers' => [
                'Accept' => 'application/vnd.sncf.transilien.od.depart+xml;vers=1'
            ],
        ]);

        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            $crawler = new Crawler($body->__toString());

            foreach ($crawler as $passages) {
                foreach ($passages->childNodes as $passage) {
                    if ($passage->nodeName == 'train') {
                        $train = array();

                        foreach ($passage->childNodes as $data) {
                            var_export($data);
                        }
                    }
                }
            }
        }
    }

    public function getNextTrains($station)
    {
    }

}

$api = new Api('87386003', '', '');
