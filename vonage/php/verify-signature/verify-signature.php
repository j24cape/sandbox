<?php
require_once '../vendor/autoload.php';

use Vonage\JWT\TokenGenerator;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 1));
$dotenv->load();

$signature_secret = $_ENV['VONAGE_SIGNATURE_SECRET'];

if (count($argv) !== 2) {
    echo sprintf('Usage: php %s TOKEN', basename(__FILE__)), "\n";
    exit(1);
}
$token = $argv[1];

$result = TokenGenerator::verifySignature($token, $signature_secret);
echo $result ? 'Verified' : 'Not verified', "\n";

exit;
