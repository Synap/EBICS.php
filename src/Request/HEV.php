<?php
namespace Synap\EBICS\Request;

use DOMImplementation;

class HEV extends AbstractRequest
{
    /**
     * Namespace XML pour la norme H000
     */
    const NS_H000 = 'http://www.ebics.org/H000';

    private $host_id;

    public function setHostID($host_id)
    {
        $this->host_id = $host_id;
    }

    public function __construct($host_id)
    {
        $this->setHostID($host_id);
        $this->setBody(
            $this->getDOM()->saveXML()
        );
    }

    public function getDOM()
    {
        $ns = self::NS_H000;

        $dom = (new DOMImplementation())->createDocument($ns, "ebicsHEVRequest");

        $dom->documentElement->appendChild(
            $dom->createElementNS($ns, 'HostID', $this->host_id)
        );

        return $dom;
    }
}
