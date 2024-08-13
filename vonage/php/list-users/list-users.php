<?php
require_once sprintf('%s/vendor/autoload.php', dirname(__DIR__, 1));

use Vonage\Client;
use Vonage\Client\Credentials\Keypair;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$application_id = $_ENV['VONAGE_APPLICATION_ID'];
$application_private_key = $_ENV['VONAGE_APPLICATION_PRIVATE_KEY'];

try {
    $credentials = new Keypair($application_private_key, $application_id);
    $client = new Client($credentials);
    $results = $client->users()->listUsers();
    $results->rewind();
    while ($results->valid()) {
        $user = $results->current();
        echo sprintf('%s:%s', $user->getId(), $user->getName()), "\n";
        $results->next();
    }
} catch (Exception $e) {
    echo sprintf('Exception %s: %s at line %d in %s',
        get_class($e), $e->getMessage(), $e->getLine(), $e->getFile()), "\n";
    exit(1);
}

exit;