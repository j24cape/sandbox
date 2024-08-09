<?php
require_once sprintf('%s/vendor/autoload.php', dirname(__DIR__, 1));

use OpenTok\OpenTok;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$api_key = $_ENV['VONAGE_API_KEY'];
$api_secret = $_ENV['VONAGE_API_SECRET'];

if (count($argv) !== 2) {
    echo sprintf(
        'Usage: php %s VONAGE_VIDEO_SESSION_ID', basename(__FILE__)), "\n";
    exit(1);
}
$session_id = $argv[1];

try {
    $opentok = new OpenTok($api_key, $api_secret);
    echo $opentok->generateToken($session_id);
} catch (Exception $e) {
    echo sprintf('Exception %s: %s at line %d in %s',
        get_class($e), $e->getMessage(), $e->getLine(), $e->getFile()), "\n";
    exit(1);
}

exit;
