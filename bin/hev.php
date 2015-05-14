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

$parameters = json_decode(file_get_contents('parameters.json'));

$url      = empty($argv[1]) ? $parameters->url : $argv[1];
$HostID   = empty($argv[2]) ? $parameters->host : $argv[2];

// Template pour requête HEV
$xsl = new DOMDocument();
$xsl->load('xslt/hev.xsl');

// Configuration du moteur de template
$proc = new XSLTProcessor();
$proc->setParameter('', 'HostID', $HostID);
$proc->importStylesheet($xsl);

// Récupération du résultat
$doc = $proc->transformToDoc(new DOMDocument());

$request = new Sabre\HTTP\Request('POST', $url);
$request->setBody($doc->saveXML());

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

