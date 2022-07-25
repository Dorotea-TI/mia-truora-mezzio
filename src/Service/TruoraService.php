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
     * @return void
     */
    public function createCheckPerson($nationalId, $country)
    {
        return $this->createCheck($nationalId, 'CO', 'person', 'true');
    }
    /**
     * Generar un Check
     *
     * @param string $nationalId
     * @param string $country
     * @param string $type
     * @param string $userAuthorized
     * @return void
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
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Truora-API-Key' => $this->apiKey
        ];

        if($params != null){
            $response = $this->guzzle->request($method, $path, [
                'headers' => $headers,
                'form_params' => $params
            ]);
        } else {
            $response = $this->guzzle->request($method, $path, ['headers' => $headers]);
        }

        if($response->getStatusCode() == 200){
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