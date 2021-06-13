<?php

use GuzzleHttp\Client;

require_once 'bootstrap.php';

$writer = new Writer();
$getData = json_encode($_GET, JSON_PRETTY_PRINT);
$writer->write("GET: " . $getData);

header('Content-type: text/plain');

$httpClient = new Client();
$authorizer = new Authorizer(getenv('GITHUB_APP_ID'), getenv('GITHUB_PRIVATE_PEM'), $httpClient);
$installationId = (int) $_GET['installation_id'];
$jwt = $authorizer->getJwt();
$installationAccessToken = $authorizer->getInstallationToken($jwt, $installationId);

$response = $httpClient->get('https://api.github.com/installation/repositories', [
    'headers' => [
        'Authorization' => 'Bearer ' . $installationAccessToken['token'],
        'Accept' => 'application/vnd.github.v3+json',
    ]
]);

$repositories = json_decode($response->getBody()->getContents(), true);

echo "Installation successful!\nAvailable repositories:\n";
foreach ($repositories['repositories'] as $repository) {
    echo '  ' . $repository['full_name'] . PHP_EOL;
}