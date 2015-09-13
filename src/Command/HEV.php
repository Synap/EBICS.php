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
class HEVCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('ebics:hev')
            ->setDescription('permet de connaître le protocol utilisé par le serveur')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $parameters = json_decode(file_get_contents('parameters.json'));

        $url      = $parameters->url;
        $HostID   = $parameters->host;

        // Template pour requête HEV
        $xsl = new DOMDocument();
        $xsl->load('xslt/hev.xsl');

        // Configuration du moteur de template
        $proc = new XSLTProcessor();
        $proc->setParameter('', 'HostID', $HostID);
        $proc->importStylesheet($xsl);

        // Récupération du résultat
        $doc = $proc->transformToDoc(new DOMDocument());

        $request = new Request('POST', $url);
        $request->setBody($doc->saveXML());

        $client = new Client();
        $client->addCurlSetting(CURLOPT_SSL_VERIFYPEER, false);

        $response = $client->send($request);

        $dom = new DOMDocument();
        $dom->loadXML($response->getBodyAsString());

        //echo "\nVoici la liste des versions compatibles avec ce serveur:\n\n";

        $result = array();
        foreach ($dom->getElementsByTagName('VersionNumber') as $version) {
            $result[] = array(
                $version->getAttribute('ProtocolVersion'),
                "EBICS version {$version->nodeValue}"
            );

        }

        $table = new Table($output);
        $table
            ->setHeaders(array('Protocole', 'Description'))
            ->setRows($result)
        ;
        $table->render();

    }
}
