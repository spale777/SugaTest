<?php
namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Symfony\Component\VarDumper\Caster\Caster;

/**
 * Class SugarConnectorService
 */
class SugarConnectorService
{

    /**
     * @var
     * Guzzle Http Client
     */
    protected $client;

    /**
     * @var
     * Base url that all requests will be passed to
     */
    protected $baseUrl;

    /**
     * @var
     * Url from where the OAuth token will be retrieved from
     */
    protected $getTokenUrl;

    /**
     * @var
     * SugarCRM username defined in .env file
     */
    protected $username;

    /**
     * @var
     * SugarCRM password defined in .env file
     */
    protected $password;

    /**
     * @var
     * SugarCRM client_id defined in .env file
     */
    protected $clientId;
    /**
     * @var
     * SugarCRM platform that we are logging on to, default API
     */

    protected $platform;
    /**
     * @var
     * SugarCRM grant type defined in .env file
     */

    protected $grantType;
    /**
     * @var
     * OAuth Token
     */

    protected $oauthToken;

    public function __construct(Client $client, $baseUrl, $getTokenUrl, $username, $password, $clientId, $platform, $grant_type)
    {
        $this->client = $client;
        $this->baseUrl = $baseUrl;
        $this->getTokenUrl = $this->baseUrl . $getTokenUrl;
        $this->username = $username;
        $this->password = $password;
        $this->clientId = $clientId;
        $this->platform = $platform;
        $this->grantType = $grant_type;

        if(!Cache::has('oauthToken')){
            Cache::add('oauthToken', $this->getOauthToken(), 59);
        }

        $this->oauthToken = Cache::get('oauthToken');
    }
    protected function getOauthToken()
    {
        $response = $this->client->request('POST', $this->getTokenUrl, [
            'form_params'=>[
                'grant_type' => $this->grantType,
                'username' => $this->username,
                'password' => $this->password,
                'client_id' => $this->clientId,
                'platform' => $this->platform,
            ],
        ]);

        $result = json_decode($response->getBody()->getContents());

        return $result->access_token;
    }

    public function getLeads($options = null){

        $response = $this->client->request('GET', $this->baseUrl . 'Leads', [
            'headers' => [
                'OAuth-Token' => $this->oauthToken,
            ],
            'query' => [
                'max_num' => $options['items'] ?? 20,
                'fields' => 'id'
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }


}