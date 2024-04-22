<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;

class ApiConnectorService
{
    private $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    /**
     * Fetches data from an API endpoint.
     *
     * @param string $endpoint The API endpoint URL.
     * @return array|null An array containing the data fetched from the API, or null if an error occurs.
     */
    public function getDataFromApi($endpoint){
        $client = HttpClient::create();
        $response = $client->request('GET', $endpoint);

        if($response->getStatusCode() >= 400) {
            $this->logger->error('Error occurred during the request: ' . $response->getInfo('url'));
            return null;
        }          
        $jsonData = $response->getContent();
        $arrayData = json_decode($jsonData, true);

        return $arrayData;
    }
}