<?php

namespace Mia\Truora\Service;

use GuzzleHttp\Psr7\Request;

class TruoraService
{
    /**
     * URL de la API
     */
    const BASE_URL = 'https://api.checks.truora.com/v1';
    /**
     * 
     * @var string
     */
    protected $apiKey = '';
    /**
     * @var \GuzzleHttp\Client
     */
    protected $guzzle;
    /**
     * 
     * @param string $access_token
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->guzzle = new \GuzzleHttp\Client(['base_uri' => self::BASE_URL]);
    }
    /**
     *
     * @param string $nationalId
     * @param string $country
     * @return Object
     */
    public function createCheckPerson($nationalId, $country)
    {
        return $this->createCheck($nationalId, $country, 'person', 'true');
    }
    /**
     * Generar un Check
     *
     * @param string $nationalId
     * @param string $country
     * @param string $type
     * @param string $userAuthorized
     * @return Object
     */
    public function createCheck($nationalId, $country, $type, $userAuthorized)
    {
        return $this->generateRequest('POST', '/checks', [
            'national_id' => $nationalId,
            'country' => $country,
            'type' => $type,
            'user_authorized' => $userAuthorized
        ]);
    }
    /**
     * Obtiene informacion 
     * @return Object
     */
    public function detailCheck($checkId)
    {
        return $this->generateRequest('GET', '/checks/' . $checkId);
    }
    /**
     * Funcion para generar request
     */
    protected function generateRequest($method, $path, $params = null)
    {
        $request = new Request(
            $method, 
            self::BASE_URL . $path, 
            [
                'Accept' => '*/*',
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Truora-API-Key' => $this->apiKey
            ]);

        $response = $this->guzzle->send($request, [
            'form_params' => $params
        ]);
        
        if($response->getStatusCode() == 200||$response->getStatusCode() == 201){
            return json_decode($response->getBody()->getContents());
        }
        
        return null;
    }

    /**
     * 
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }
}