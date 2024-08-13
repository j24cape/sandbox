<?php
require_once sprintf('%s/vendor/autoload.php', dirname(__DIR__, 1));

use Vonage\Client;
use Vonage\Client\Credentials\Keypair;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$application_id = $_ENV['VONAGE_APPLICATION_ID'];
$application_private_key = $_ENV['VONAGE_APPLICATION_PRIVATE_KEY'];

if (count($argv) !== 2) {
    echo sprintf('Usage: php %s VONAGE_USER_NAME', basename(__FILE__)), "\n";
    exit(1);
}
$user_name = $argv[1];

try {
    $credentials = new Keypair($application_private_key, $application_id);
    $client = new Client($credentials);
    $claims = [
        'subject' => $user_name,
        'acl' => [
            'paths' => [
                '/*/rtc/**' => (object) [],
                '/*/users/**' => (object) [],
                '/*/conversations/**' => (object) [],
                '/*/sessions/**' => (object) [],
                '/*/devices/**' => (object) [],
                '/*/image/**' => (object) [],
                '/*/media/**' => (object) [],
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
