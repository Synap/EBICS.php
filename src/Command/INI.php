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
class INICommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('ebics:ini')
            ->setDescription('Envoie une requête INI')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Récupération des paramètres de connexion
        $parameters = json_decode(file_get_contents('parameters.json'));

        // Récupération du certificat X509
        $cert = file_get_contents('test/fixtures/keys/A005/cert.pem');

        $cert_details = openssl_x509_parse($cert);

        $params = array(
            'X509IssuerName' => $cert_details['name'],
            'X509SerialNumber' => $cert_details['serialNumber'],
            'X509Certificate' => str_replace(
                array(
                    '-----BEGIN CERTIFICATE-----',
                    '-----END CERTIFICATE-----',
                    "\n"
                ),
                '',
                $cert
            )
        );


        $cert_details = openssl_pkey_get_details(openssl_pkey_get_public($cert));

        $params = array_merge(
            array(
                'Modulus' => base64_encode($cert_details['rsa']['n']),
                'Exponent' => base64_encode($cert_details['rsa']['e'])
            ),
            $params
        );

        // Attention, la cohérence entre les différente valeurs n'est pas vérifiée dans
        // la phase d'initialisation. Seule la longueur des valeurs l'est

        // Création de la signature A005
        $xsl = new DOMDocument();
        $xsl->load('xslt/SignaturePubKeyOrderData.xsl');

        $proc = new XSLTProcessor();
        $proc->setParameter('', 'X509IssuerName', $params['X509IssuerName']);
        $proc->setParameter('', 'X509SerialNumber', $params['X509SerialNumber']);
        $proc->setParameter('', 'X509Certificate', $params['X509Certificate']);
        $proc->setParameter('', 'Modulus', $params['Modulus']);
        $proc->setParameter('', 'Exponent', $params['Exponent']);
        $proc->setParameter('', 'PartnerID', $parameters->partner);
        $proc->setParameter('', 'UserID', $parameters->user);
        $proc->setParameter('', 'TimeStamp', date('c'));
        $proc->importStylesheet($xsl);

        $A005 = $proc->transformToXML(new DOMDocument());

        // On compresse et on encode en base64
        $A005 = gzcompress($A005);
        $A005 = base64_encode($A005);

        $xsl = new DOMDocument();
        $xsl->load('xslt/ebicsUnsecuredRequest.xsl');
        $proc->importStylesheet($xsl);
        $proc->setParameter('', 'HostID', $parameters->host);
        $proc->setParameter('', 'OrderData', $A005);

        $xml = $proc->transformToXML(new DOMDocument());

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
