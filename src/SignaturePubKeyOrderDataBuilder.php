<?php
namespace Synap\EBICS;

use DOMImplementation;

class SignaturePubKeyOrderDataBuilder
{
    /**
     * @return DOMDocument
     */
    public function build($client)
    {
        $ds = 'http://www.w3.org/2000/09/xmldsig#';

        $dom = (new DOMImplementation())->createDocument("http://www.ebics.org/S001", "SignaturePubKeyOrderData");

        $root = $dom->documentElement;

        $info = $root->appendChild(
            $dom->createElement('SignaturePubKeyInfo')
        );

        $root->appendChild(
            $dom->createElement('PartnerID', $client->partner_id)
        );

        $root->appendChild(
            $dom->createElement('UserID', $client->user_id)
        );

        $data = $info->appendChild(
            $dom->createElementNS($ds, 'ds:X509Data')
        );

        $issuer = $data->appendChild(
            $dom->createElementNS($ds, 'ds:X509IssuerSerial')
        );

        $issuer->appendChild(
            $dom->createElementNS($ds, 'ds:X509IssuerName', $data->getIssuerName())
        );

        $issuer->appendChild(
            $dom->createElementNS($ds, 'ds:X509SerialNumber', $data->getSerialNumber())
        );

        $issuer = $data->appendChild(
            $dom->createElementNS($ds, 'ds:X509Certificate', $data->getX509Certificate())
        );

        $value = $info->appendChild(
            $dom->createElement('PubKeyValue')
        );

        $rsa = $value->appendChild(
            $dom->createElementNS($ds, 'ds:RSAKeyValue')
        );

        $rsa->appendChild(
            $dom->createElementNS($ds, 'ds:Modulus', $data->getModulus())
        );

        $rsa->appendChild(
            $dom->createElementNS($ds, 'ds:Exponent', $data->getExponent())
        );

        $value->appendChild(
            $dom->createElement('TimeStamp', '2015-03-06T18:42:24.376+01:00')
        );

        $info->appendChild(
            $dom->createElement('SignatureVersion', 'A005')
        );

        return $dom;
    }
}

