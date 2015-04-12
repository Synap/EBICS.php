<?php
namespace Synap\EBICS\Request;

use PHPUnit_Framework_TestCase;

class HEVTest extends PHPUnit_Framework_TestCase
{
    public function testValidRequest()
    {
        $xsd = __DIR__ . '/../xsd/H000/ebics_hev.xsd';

            echo (new HEV('ABCD'))
                ->getDOM()
                ->saveXML();

        $this->assertTrue(
            (new HEV('ABCD'))
                ->getDOM()
                ->schemaValidate($xsd)
        );
    }
}

