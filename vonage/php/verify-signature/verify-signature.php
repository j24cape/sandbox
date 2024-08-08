<?php
require_once sprintf('%s/vendor/autoload.php', dirname(__DIR__, 1));

use Vonage\JWT\TokenGenerator;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$signature_secret = $_ENV['VONAGE_SIGNATURE_SECRET'];

if (count($argv) !== 2) {
    echo sprintf('Usage: php %s TOKEN', basename(__FILE__)), "\n";
    exit(1);
}
$token = $argv[1];

try {
    $result = TokenGenerator::verifySignature($token, $signature_secret);
    echo $result ? 'Verified' : 'Not verified', "\n";
} catch (Exception $e) {
    echo sprintf('Exception %s: %s at line %d in %s',
        get_class($e), $e->getMessage(), $e->getLine(), $e->getFile()), "\n";
    exit(1);
}

exit;
