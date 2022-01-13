<?php

namespace Ovillafuerte94\QvapayLaravel;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use GuzzleHttp\Exception\RequestException;
use Ovillafuerte94\QvapayLaravel\Exceptions\QvapayException;

class ApiClient
{
    /**
     * Application ID.
     *
     * @var string
     */
    protected $app_id;

    /**
     * Application secret key.
     *
     * @var string
     */
    protected $app_secret;

    /**
     * Api version.
     *
     * @var string
     */
    protected $version = 'v1';

    /**
     * Api URL.
     *
     * @var string
     */
    protected $api_url = 'https://qvapay.com/api/';

    /**
     * Http Client.
     *
     * @var GuzzleHttp\Client
     */
    protected $http_client;

    /**
     * Construct.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->app_id = $config['app_id'];
        $this->app_secret = $config['app_secret'];

        /* prepare http client */
        $this->http_client = new Client([
            'base_uri' => $this->api_url . $this->version . '/',
        ]);
    }

    /**
     * Get application information.
     *
     * @return mixed
     */
    public function info()
    {
        return $this->request('info');
    }

    /**
     * Create payment invoice.
     *
     * @param array $data
     *
     * @return mixed
     */
    public function createInvoice(array $data)
    {
        if (!Arr::exists($data, 'amount')) {
            throw new \InvalidArgumentException('The amount parameter is required');
        }

        if (!is_numeric($data['amount'])) {
            throw new \InvalidArgumentException('The amount parameter not valid');
        }

        if (!Arr::exists($data, 'description') && Str::length($data['description']) > 300) {
            throw new \InvalidArgumentException('The description parameter cannot contain more than 300 characters');
        }

        return $this->request('create_invoice', $data);
    }

    /**
     * Gets transactions list, paginated by 50 items per request.
     *
     * @param int $page Page number
     *
     * @return mixed
     */
    public function transactions(int $page = 1)
    {
        return $this->request('transactions', [
            'page' => $page,
        ]);
    }

    /**
     * Get Transaction by UUID.
     *
     * @param string $uuid Universal Unique Identifier
     *
     * @return mixed
     */
    public function transaction($uuid)
    {
        /* verify uuid */
        if (preg_match('/^[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}$/i', $uuid) !== 1) {
            throw new \InvalidArgumentException('The parameter uuid not valid');
        }

        return $this->request('transaction/' . $uuid);
    }

    /**
     * Get your balance.
     *
     * @return mixed
     */
    public function balance()
    {
        return $this->request('balance');
    }

    /**
     * Request to Qvapay api endpoint.
     *
     * @param string $endpoint
     * @param array  $data
     *
     * @return mixed
     */
    public function request($endpoint, array $data = [])
    {
        try {
            $request = $this->http_client->request('GET', $endpoint, [
                'query' => array_merge($data, [
                    'app_id' => $this->app_id,
                    'app_secret' => $this->app_secret,
                ]),
            ]);
        } catch (RequestException $e) {
            throw new QvapayException(
                "[{$e->getCode()}] {$e->getMessage()}",
                (int) $e->getCode()
            );
        }

        if ($request->getStatusCode() != 200) {
            throw new QvapayException(
                sprintf('[%d] Error connecting to (%s)', $request->getStatusCode(), (string) $request->getUri()),
                $request->getStatusCode(),
            );
        } else {
            $response = $request->getBody()->getContents();
            if (self::isJson($response)) {
                return json_decode($response);
            } else {
                return $response;
            }
        }
    }

    /**
     * Check if a string is JSON.
     *
     * @param string $string
     *
     * @return bool
     */
    public static function isJson(string $string): bool
    {
        json_decode($string);

        return json_last_error() === JSON_ERROR_NONE;
    }
}
