<?php

namespace App\Services\AdobeConnect;

use Illuminate\Support\Facades\Log;

class AdobeConnectClientService
{
    private $cookie;
    private $curl;

    private $api;
    private AdobeConnectSettingService $service;

    public function __construct() {
        $this->service = app()->make(AdobeConnectSettingService::class);
        $this->authenticate();
    }
    private function authenticate()
    {
        $this->cookie = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'cookie_' . time() . '.txt';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_REFERER, $this->service->getApiUrl());
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        $this->curl = $ch;
        $api_rqst = $this->makeRequest('common-info');
        $this->api = $api_rqst["common"]["cookie"];
        $this->makeAuth();
    }

    /**
     * make auth-request with admin username and password
     */
    public function makeAuth() {
        $user = $this->service->getSettings()->username;
        $pass = $this->service->getSettings()->password;

        $this->makeRequest('login',
            array(
                'login' => $user,
                'password' => $pass,
            )
        );

        return $this;
    }

    public function __destruct() {
        $api_rqst = $this->makeRequest('logout');
        @curl_close($this->curl);
    }

    public function makeRequest($action, array $params = array()) {
        $url = $this->service->getApiUrl();

        $url .= 'xml?action=' . $action;
        if ($action !== "common-info") {
            $url .= '&session=' . $this->api;
        }
        if (isset($params) && is_array($params) && count($params) > 0) {
            $url .= '&' . http_build_query($params);
        }
        Log::info($url);
        curl_setopt($this->curl, CURLOPT_URL, $url);
        $result = curl_exec($this->curl);

        $xml = simplexml_load_string($result);

        $json = json_encode($xml);
        $data = json_decode($json, true);
        if (!isset($data['status']['@attributes']['code']) || $data['status']['@attributes']['code'] !== 'ok') {
            //throw new Exception('Coulnd\'t perform the action: '.$action);
        }
        return $data;
    }

}
