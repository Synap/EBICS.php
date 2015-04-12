<?php
namespace Synap\EBICS\Request;

use Sabre\HTTP;

abstract class AbstractRequest extends HTTP\Request
{
    private $client;

    public function __construct($client)
    {
        parent::__construct('POST', $client->url);
        $this->client = $client;
    }

    /**
     * @return \DOMDocument
     */
    abstract public function getDOM();
}
