<?php
namespace Synap\EBICS\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use DOMDocument;
use XSLTProcessor;
use Sabre\HTTP\Request;
use Sabre\HTTP\Client;

/**
 * Exemple de requête HEV
 *
 * Une requête HEV permet de connaître le protocol utilisé par le serveur
 *
 * utilisation du script:
 *
 *     php bin/hev.php http://127.0.0.1/ebics monHostID
 */
class HIACommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('ebics:hia')
            ->setDescription('Envoie une requête HIA')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $parameters = json_decode(file_get_contents('parameters.json'));

        $X002 = file_get_contents('test/fixtures/keys/X002/cert.pem');

        $cert_details = openssl_x509_parse($X002);

        $params['X002'] = array(
            'X509IssuerName' => $cert_details['name'],
            'X509SerialNumber' => $cert_details['serialNumber'],
            'X509Certificate' => str_replace(
                array(
                    '-----BEGIN CERTIFICATE-----',
                    '-----END CERTIFICATE-----',
                    "\n"
                ),
                '',
                $X002
            )
        );


        $cert_details = openssl_pkey_get_details(openssl_pkey_get_public($X002));

        $params['X002'] = array_merge(
            array(
                'Modulus' => base64_encode($cert_details['rsa']['n']),
                'Exponent' => base64_encode($cert_details['rsa']['e'])
            ),
            $params['X002']
        );

        $E002 = file_get_contents('test/fixtures/keys/E002/cert.pem');

        $cert_details = openssl_x509_parse($E002);

        $params['E002'] = array(
            'X509IssuerName' => $cert_details['name'],
            'X509SerialNumber' => $cert_details['serialNumber'],
            'X509Certificate' => str_replace(
                array(
                    '-----BEGIN CERTIFICATE-----',
                    '-----END CERTIFICATE-----',
                    "\n"
                ),
                '',
                $E002
            )
        );

        $cert_details = openssl_pkey_get_details(openssl_pkey_get_public($E002));

        $params['E002'] = array_merge(
            array(
                'Modulus' => base64_encode($cert_details['rsa']['n']),
                'Exponent' => base64_encode($cert_details['rsa']['e'])
            ),
            $params['E002']
        );

        // Attention, la cohérence entre les différente valeurs n'est pas vérifiée dans
        // la phase d'initialisation. Seule la longueur des valeurs l'est

        // Création de la signature A005

        $data = '<?xml version="1.0" encoding="UTF-8"?>
        <HIARequestOrderData xmlns="http://www.ebics.org/H003" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.ebics.org/H003 http://www.ebics.org/H003/ebics_orders.xsd">
          <AuthenticationPubKeyInfo>
            <ds:X509Data>
              <ds:X509IssuerSerial>
                <ds:X509IssuerName>'.$params['X002']['X509IssuerName'].'</ds:X509IssuerName>
                <ds:X509SerialNumber>'.$params['X002']['X509SerialNumber'].'</ds:X509SerialNumber>
              </ds:X509IssuerSerial>
              <ds:X509Certificate>'.$params['X002']['X509Certificate'].'</ds:X509Certificate>
            </ds:X509Data>
            <PubKeyValue>
              <ds:RSAKeyValue>
                <ds:Modulus>'.$params['X002']['Modulus'].'</ds:Modulus>
                <ds:Exponent>'.$params['X002']['Exponent'].'</ds:Exponent>
              </ds:RSAKeyValue>
            </PubKeyValue>
            <AuthenticationVersion>X002</AuthenticationVersion>
          </AuthenticationPubKeyInfo>
          <EncryptionPubKeyInfo>
            <ds:X509Data>
              <ds:X509IssuerSerial>
                <ds:X509IssuerName>'.$params['E002']['X509IssuerName'].'</ds:X509IssuerName>
                <ds:X509SerialNumber>'.$params['E002']['X509SerialNumber'].'</ds:X509SerialNumber>
              </ds:X509IssuerSerial>
              <ds:X509Certificate>'.$params['E002']['X509Certificate'].'</ds:X509Certificate>
            </ds:X509Data>
            <PubKeyValue>
              <ds:RSAKeyValue>
                <ds:Modulus>'.$params['E002']['Modulus'].'</ds:Modulus>
                <ds:Exponent>'.$params['E002']['Exponent'].'</ds:Exponent>
              </ds:RSAKeyValue>
            </PubKeyValue>
            <EncryptionVersion>E002</EncryptionVersion>
          </EncryptionPubKeyInfo>
          <PartnerID>'.$parameters->partner.'</PartnerID>
          <UserID>'.$parameters->user.'</UserID>
        </HIARequestOrderData>';


        $data = base64_encode(gzcompress($data));


        $xml = '<?xml version="1.0"?>
        <ebicsUnsecuredRequest xmlns="http://www.ebics.org/H003" Revision="1" Version="H003">
          <header authenticate="true">
            <static>
              <HostID>'.$parameters->host.'</HostID>
              <PartnerID>'.$parameters->partner.'</PartnerID>
              <UserID>'.$parameters->user.'</UserID>
              <OrderDetails>
                <OrderType>HIA</OrderType>
                <OrderID>A102</OrderID>
                <OrderAttribute>DZNNN</OrderAttribute>
              </OrderDetails>
              <SecurityMedium>0000</SecurityMedium>
            </static>
            <mutable/>
          </header>
          <body>
            <DataTransfer>
              <OrderData>'.$data.'</OrderData>
            </DataTransfer>
          </body>
        </ebicsUnsecuredRequest>';


        $request = new Request('POST', $parameters->url);
        $request->setBody($xml);

        $client = new Client();
        $client->addCurlSetting(CURLOPT_SSL_VERIFYPEER, false);

        $response = $client->send($request);

        $dom = new DOMDocument();
        $dom->formatOutput = true;
        $dom->loadXML($response->getBodyAsString());

        echo $dom->saveXML();
    }
}
