<?php
require_once sprintf('%s/vendor/autoload.php', dirname(__DIR__, 1));

use Vonage\Client;
use Vonage\Client\Credentials\Keypair;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$application_id = $_ENV['VONAGE_APPLICATION_ID'];
$application_private_key = $_ENV['VONAGE_APPLICATION_PRIVATE_KEY'];

if (count($argv) !== 2) {
    echo sprintf(
        'Usage: php %s VONAGE_VIDEO_SESSION_ID', basename(__FILE__)), "\n";
    exit(1);
}
$session_id = $argv[1];

try {
    $credentials = new Keypair($application_private_key, $application_id);
    $client = new Client($credentials);
    $token = $client->video()->generateClientToken($session_id);
    echo $token, "\n";
} catch (Exception $e) {
    echo sprintf('Exception %s: %s at line %d in %s',
        get_class($e), $e->getMessage(), $e->getLine(), $e->getFile()), "\n";
    exit(1);
}

exit;
