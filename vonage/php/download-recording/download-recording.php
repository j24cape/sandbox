<?php
require_once sprintf('%s/vendor/autoload.php', dirname(__DIR__, 1));

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request;
use Vonage\Client;
use Vonage\Client\Credentials\Keypair;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$application_id = $_ENV['VONAGE_APPLICATION_ID'];
$application_private_key = $_ENV['VONAGE_APPLICATION_PRIVATE_KEY'];
$fqdn_api = $_ENV['VONAGE_FQDN_API'];

if (count($argv) !== 2) {
    echo sprintf(
        'Usage: php %s VONAGE_RECORDING_ID', basename(__FILE__)), "\n";
    exit(1);
}
$recording_id = $argv[1];

$recording_url = sprintf('https://%s/v1/files/%s', $fqdn_api, $recording_id);

try {
    $credentials = new Keypair($application_private_key, $application_id);
    $client = new Client($credentials);
    $token = $client->generateJwt();
    $authorization = sprintf('Bearer %s', $token->toString());
    $request = new Request('GET', $recording_url, [
        'Authorization' => $authorization,
    ]);
    $guzzle_client = new GuzzleClient();
    $response = $guzzle_client->send($request);
    $filename = sprintf('%s.mp3', $recording_id);
    $result = file_put_contents($filename, $response->getBody());
    if ($result === false) {
        throw new Exception(sprintf('Failed to write file %s', $filename));
    }
    echo sprintf('Succeeded to write file %s in %d bytes',
        $filename, $result), "\n";
} catch (Exception $e) {
    echo sprintf('Exception %s: %s at line %d in %s',
        get_class($e), $e->getMessage(), $e->getLine(), $e->getFile()), "\n";
    exit(1);
}

exit;
