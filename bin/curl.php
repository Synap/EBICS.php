<?php
/**
 * Exemple de requête HTTP
 *
 * Ce script contient un exemple de code pour effectuer
 * une requête POST en utilisant la bibliothèque curl
 *
 * exemple: php bin/curl.php http://127.0.0.1/ebics test/fixtures/sample.xml
 */

$url      = $argv[1];
$filename = $argv[2];
$xml      = file_get_contents($filename);

$ch = curl_init();

curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);

/**
 * @todo intégrer la vérification SSL basée sur le CA
 */
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$result = curl_exec($ch);

curl_close($ch);

echo $result;
