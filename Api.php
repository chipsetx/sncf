<?php

namespace Juuuuuu\Sncf;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class Api
{
    protected $client;
    private $codeUIC;
    private $username;
    private $password;
    private $destinations = [];

    public function __construct($codeUIC, $username, $password, array $destinations = [])
    {
        $this->codeUIC = $codeUIC;
        $this->username = $username;
        $this->password = $password;
        $this->destinations = $destinations;
        $this->client = new Client();
    }

    public function getNextTrains()
    {
        try {
            $response = $this->client->request('GET', 'http://api.transilien.com/gare/'.$this->codeUIC.'/depart/', [
                'auth' => [$this->username, $this->password],
                'headers' => [
                    'Accept' => 'application/vnd.sncf.transilien.od.depart+xml;vers=1'
                ],
            ]);

            if ($response->getStatusCode() == 200) {
                $trains = [];
                $body = $response->getBody();

                $crawler = new Crawler($body->__toString());

                foreach ($crawler as $passages) {
                    foreach ($passages->childNodes as $key => $passage) {
                        if ($passage->nodeName == 'train') {
                            foreach ($passage->childNodes as $data) {
                                if ($data->nodeName == 'date') {
                                    $trains[$key]['date'] = $data->nodeValue;
                                }
                                if ($data->nodeName == 'num') {
                                    $trains[$key]['num'] = $data->nodeValue;
                                }
                                if ($data->nodeName == 'miss') {
                                    $trains[$key]['miss'] = $data->nodeValue;
                                }
                                if ($data->nodeName == 'term') {
                                    $trains[$key]['term'] = $data->nodeValue;
                                }
                                if ($data->nodeName == 'etat') {
                                    $trains[$key]['etat'] = $data->nodeValue;
                                }
                            }
                        }
                    }
                }

                return $this->filterByDestination($trains);
            }
        } catch (Exception $e) {
            return json_encode(['status' => 520, 'message' => $e->getMessage()]);
        }

        return json_encode(['status' => 400, 'message' => 'Bad request']);
    }

    private function filterByDestination($trains)
    {
        foreach ($trains as $key => $train) {
            if (!in_array($train['term'], $this->destinations)) {
                unset($trains[$key]);
            }
        }

        return $trains;
    }
}
