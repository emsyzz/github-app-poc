<?php

use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Authorizer
{
    private int $appId;
    private string $privatePemPath;
    private Client $httpClient;

    public function __construct(int $appId, string $privatePemPath, Client $httpClient = null) {
        $this->appId = $appId;
        $this->privatePemPath = $privatePemPath;
        $this->httpClient = $httpClient ?? new Client();
    }

    public function getJwt(): string
    {
        $privatePem = file_get_contents($this->privatePemPath);
        $privateKey = openssl_pkey_get_private($privatePem);

        $payload = [
            'iat' => time() - 60,
            'exp' => time() + (10 * 60),
            'iss' => $this->appId,
        ];

        return JWT::encode($payload, $privateKey, 'RS256');
    }

    /**
     * @param string $jwt
     * @param int $installationId
     * @return array
     * @throws GuzzleException
     * @throws Exception
     */
    public function getInstallationToken(string $jwt, int $installationId): array
    {
        $response = $this->httpClient->post(
            'https://api.github.com/app/installations/' . $installationId . '/access_tokens',
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $jwt,
                    'Accept' => 'application/vnd.github.v3+json',
                ],
            ]
        );

        if ($response->getStatusCode() !== 201) {
            throw new Exception('Unable to create access token for installation #' . $installationId);
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}
