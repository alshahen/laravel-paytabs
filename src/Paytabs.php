<?php
namespace Alshahen\Paytabs;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

class Paytabs
{
    const API_ENDPOINT = 'https://secure.paytabs.sa';

    protected $fields;

    protected $client;

    protected $serverKey;


    /**
     * Paytabs constructor.
     * @param array $fields
     * @param string $serverKey
     * @param Client|null $client
     */
    public function __construct(string $serverKey, Client $client = null)
    {
        $this->client = $client ?? new Client(['base_uri' => self::API_ENDPOINT]);
        $this->serverKey = $serverKey;
    }

    /**
     * Set request fields
     * @param array $fields
     */
    public function setFields(array $fields){
        $this->fields = $fields;
    }

    /**
     * Make Payment request
     * @return ResponseInterface
     * @throws RequestException
     */
    public function payment()
    {
        try {
            $response = $this->client->request('POST', '/payment/request',
                [
                    'headers' => [
                        'authorization' => $this->serverKey,
                    ],
                    'json' => $this->fields
                ]);

            return $response;

        } catch (RequestException $e) {
            echo "Error Request !";
        }
    }

    /**
     * Verify request signature , to ensure that the redirect request is genuine
     * @param array $responseFields
     * @return bool
     */
    public function verifyPayment(array $responseFields)
    {
        $responseSignature = $responseFields['signature'];

        unset($responseFields['signature']);

        ksort($responseFields);

        $query = http_build_query($responseFields);

        $signature = hash_hmac('sha256', $query, $this->serverKey);

        return hash_equals($responseSignature ,$signature);

    }

    public function redirect()
    {
        $payment = $this->payment();

        if($payment)
            return header('Location: ' . json_decode($payment->getBody()->getContents())->redirect_url);

    }

}