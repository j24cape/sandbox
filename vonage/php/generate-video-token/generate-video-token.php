<?php
require_once sprintf('%s/vendor/autoload.php', dirname(__DIR__, 1));

use Vonage\Client;
use Vonage\Client\Credentials\Keypair;
use Vonage\Video\Role;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->load();

$application_id = $_ENV['VONAGE_APPLICATION_ID'];
$application_private_key = $_ENV['VONAGE_APPLICATION_PRIVATE_KEY'];
$jwt_ttl = (int) $_ENV['JWT_TTL'] ?? 900;

if (count($argv) !== 2) {
    echo sprintf(
        'Usage: php %s VONAGE_VIDEO_SESSION_ID', basename(__FILE__)), "\n";
    exit(1);
}
$session_id = $argv[1];

try {
    $credentials = new Keypair($application_private_key, $application_id);
    $client = new Client($credentials);
    $options = [
        'ttl' => $jwt_ttl,
        'role' => Role::PUBLISHER,
    ];
    $token = $client->video()->generateClientToken($session_id, $options);
    echo $token, "\n";
} catch (Exception $e) {
    echo sprintf('Exception %s: %s at line %d in %s',
        get_class($e), $e->getMessage(), $e->getLine(), $e->getFile()), "\n";
    exit(1);
}

exit;
