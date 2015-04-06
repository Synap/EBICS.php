<?php
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

$ch = curl_init();

curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_HEADER, false);

/**
 * @todo intégrer la vérification SSL basée sur le CA
 */
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$result = curl_exec($ch);

$dom = new DOMDocument();
$dom->loadXML($result);

echo "\nVoici la liste des versions compatibles avec ce serveur:\n\n";

foreach ($dom->getElementsByTagName('VersionNumber') as $version) {

    echo "- {$version->getAttribute('ProtocolVersion')} (EBICS version {$version->nodeValue})\n";

}

echo "\n";

curl_close($ch);
