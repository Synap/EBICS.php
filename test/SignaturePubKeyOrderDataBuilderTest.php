<?php
namespace Synap\EBICS;

use PHPUnit_Framework_TestCase;

class SignaturePubKeyOrderDataBuilderTest extends PHPUnit_Framework_TestCase
{
    public function testInstanciate()
    {
        $builder = new SignaturePubKeyOrderDataBuilder();
    }
}
