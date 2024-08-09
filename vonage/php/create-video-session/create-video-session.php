<?php
require_once sprintf('%s/vendor/autoload.php', dirname(__DIR__, 1));

use OpenTok\{
    MediaMode,
    OpenTok,
};

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$api_key = $_ENV['VONAGE_API_KEY'];
$api_secret = $_ENV['VONAGE_API_SECRET'];

try {
    $opentok = new OpenTok($api_key, $api_secret);
    $options = [
        'mediaMode' => MediaMode::ROUTED,
    ];
    $session = $opentok->createSession($options);
    echo $session->getSessionId();
} catch (Exception $e) {
    echo sprintf('Exception %s: %s at line %d in %s',
        get_class($e), $e->getMessage(), $e->getLine(), $e->getFile()), "\n";
    exit(1);
}

exit;
