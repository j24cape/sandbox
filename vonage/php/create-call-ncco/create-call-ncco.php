<?php
require_once sprintf('%s/vendor/autoload.php', dirname(__DIR__, 1));

use Vonage\Client;
use Vonage\Client\Credentials\Keypair;
use Vonage\Voice\{
    Endpoint\Phone,
    OutboundCall,
};
use Vonage\Voice\NCCO\{
    Action\Talk,
    NCCO,
};

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $application_id = $_ENV['VONAGE_APPLICATION_ID'];
    $application_private_key = $_ENV['VONAGE_APPLICATION_PRIVATE_KEY'];
    $vonage_number = $_ENV['VONAGE_NUMBER'];
    $to_number = $_ENV['TO_NUMBER'];
    
    $keypair = new Keypair(
        $application_private_key,
        $application_id, 
    );
    $client = new Client($keypair);
    
    $outbound_call = new OutboundCall(
        new Phone($to_number),
        new Phone($vonage_number),
    );
    $ncco = new NCCO();
    $talk = new Talk('This is a text to speech call from Vonage');
    $ncco->addAction($talk);
    $outbound_call->setNCCO($ncco);
    
    $response = $client->voice()->createOutboundCall($outbound_call);
    var_dump($response);
} catch (Exception $e) {
    echo sprintf('Exception %s: %s at line %d in %s',
        get_class($e), $e->getMessage(), $e->getLine(), $e->getFile()), "\n";
    exit(1);
}

exit;
