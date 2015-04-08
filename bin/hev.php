<?php
require_once 'vendor/autoload.php';

/**
 * Exemple de requête HEV
 *
 * Une requête HEV permet de connaître le protocol utilisé par le serveur
 *
 * utilisation du script:
 *
 *     php bin/hev.php http://127.0.0.1/ebics monHostID
 */

$url      = $argv[1];
$HostID   = $argv[2];

$xml  = '<ebicsHEVRequest xmlns="http://www.ebics.org/H000">';
$xml .= "<HostID>{$HostID}</HostID>";
$xml .= '</ebicsHEVRequest>';

$request = new Sabre\HTTP\Request('POST', $url);
$request->setBody($xml);

$client = new Sabre\HTTP\Client();
$client->addCurlSetting(CURLOPT_SSL_VERIFYPEER, false);

$response = $client->send($request);

$dom = new DOMDocument();
$dom->loadXML($response->getBodyAsString());

echo "\nVoici la liste des versions compatibles avec ce serveur:\n\n";

foreach ($dom->getElementsByTagName('VersionNumber') as $version) {

    echo "- {$version->getAttribute('ProtocolVersion')} (EBICS version {$version->nodeValue})\n";

}

echo "\n";

