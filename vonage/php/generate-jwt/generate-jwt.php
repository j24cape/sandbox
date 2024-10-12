<?php
require_once sprintf('%s/vendor/autoload.php', dirname(__DIR__, 1));

use Vonage\Client;
use Vonage\Client\Credentials\Keypair;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->load();

$application_id = $_ENV['VONAGE_APPLICATION_ID'];
$application_private_key = $_ENV['VONAGE_APPLICATION_PRIVATE_KEY'];
$jwt_ttl = (int) $_ENV['JWT_TTL'] ?? 900;

if (count($argv) !== 2) {
    echo sprintf('Usage: php %s VONAGE_USER_NAME', basename(__FILE__)), "\n";
    exit(1);
}
$user_name = $argv[1];

try {
    $credentials = new Keypair($application_private_key, $application_id);
    $client = new Client($credentials);
    $claims = [
        'sub' => $user_name,
        'ttl' => $jwt_ttl,
        'acl' => [
            '/*/rtc/**' => (object) [],
            'paths' => [
                '/*/users/**' => (object) [],
                '/*/conversations/**' => (object) [],
                '/*/sessions/**' => (object) [],
                '/*/devices/**' => (object) [],
                '/*/image/**' => (object) [],
                '/*/media/**' => (object) [],
                '/*/applications/**' => (object) [],
                '/*/push/**' => (object) [],
                '/*/knocking/**' => (object) [],
                '/*/legs/**' => (object) [],
            ]
        ]
    ];  
    $token = $client->generateJwt($claims);
    echo $token->toString(), "\n";
} catch (Exception $e) {
    echo sprintf('Exception %s: %s at line %d in %s',
        get_class($e), $e->getMessage(), $e->getLine(), $e->getFile()), "\n";
    exit(1);
}

exit;
