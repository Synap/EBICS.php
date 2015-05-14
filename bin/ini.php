<?php
require_once 'vendor/autoload.php';

$parameters = json_decode(file_get_contents('parameters.json'));

$cert = '-----BEGIN CERTIFICATE-----
MIIDXTCCAkWgAwIBAgIJALOWNvSjrcFSMA0GCSqGSIb3DQEBCwUAMEUxCzAJBgNV
BAYTAkFVMRMwEQYDVQQIDApTb21lLVN0YXRlMSEwHwYDVQQKDBhJbnRlcm5ldCBX
aWRnaXRzIFB0eSBMdGQwHhcNMTUwNDEyMjE0MDEyWhcNMTYwNDExMjE0MDEyWjBF
MQswCQYDVQQGEwJBVTETMBEGA1UECAwKU29tZS1TdGF0ZTEhMB8GA1UECgwYSW50
ZXJuZXQgV2lkZ2l0cyBQdHkgTHRkMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIB
CgKCAQEAwaFcfrIeVOlSmWgcwNYkgdjoRns+lsbnWFVk1uQT7b4091LCCLk46DIV
BTnOYAhHHDhMDzHmN84Cjh0XzWqF/blZC2Wu5EalengeZZgS5MSNJeDc2PSvMYOF
Psz6+0pLhfpRri8NsDSRgF3pN1dEg+Pni0mn6VIRnH3a/80oGtSVwdwogpORSfYM
LkdqxS4FW7N5SPkbLgBSgpHm0Gcx4qn7KVTq1n0UDkX8scNDDPVJdwzD3lvoRu0X
Kg9fqgWr2ICEq8ikSf7zEgMTSRpxoXotUAapsdJr0/JXUJOrXWFkGMfYTFKoroSp
PQMbOwchv53gnwvex4pnZjdEijRCgQIDAQABo1AwTjAdBgNVHQ4EFgQUQnnyZlsl
wz4T+Ds6F9fj44gxFiswHwYDVR0jBBgwFoAUQnnyZlslwz4T+Ds6F9fj44gxFisw
DAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQsFAAOCAQEAdRPcgpn4P/UM4Z0SCoLW
9Wo+GbXliwfDAD2YxumQ+eATeIJxQ58Hgtld4vgd7GwdwNVP9YiHJ2n4CmfafxE6
JpJGtZsjuaKG/tF0+QnAYbu7E+0PjHvTj4626PeyMsnGY75CVktJwhAoBOnBp0yl
AAbmBFyk4MnDDoCWVol3cUyoZTU3ES66zd5VpU201tAQgDvU8AK7qd/HltoksiNF
mnOdgVTmHOAcelz7F/WuhoTATL+DwVQtwg2xB/pR65q6Qircpk5P4c7gxbcfqmoF
VOOGjjaucS3ggcWhwki4JCVCbHjSv5Mi6WPTvx1j40Tw8/98gFSlUZjVzG0zkxkd
Kw==
-----END CERTIFICATE-----';

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

$A005 = '<?xml version="1.0" encoding="UTF-8"?>
<SignaturePubKeyOrderData xmlns="http://www.ebics.org/S001" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.ebics.org/S001 http://www.ebics.org/S001/ebics_signature.xsd">
  <SignaturePubKeyInfo>
    <ds:X509Data>
      <ds:X509IssuerSerial>
        <ds:X509IssuerName>'.$params['X509IssuerName'].'</ds:X509IssuerName>
        <ds:X509SerialNumber>'.$params['X509SerialNumber'].'</ds:X509SerialNumber>
      </ds:X509IssuerSerial>
      <ds:X509Certificate>'.$params['X509Certificate'].'</ds:X509Certificate>
    </ds:X509Data>
    <PubKeyValue>
      <ds:RSAKeyValue>
        <ds:Modulus>'.$params['Modulus'].'</ds:Modulus>
        <ds:Exponent>'.$params['Exponent'].'</ds:Exponent>
      </ds:RSAKeyValue>
      <TimeStamp>2015-03-06T18:42:24.376+01:00</TimeStamp>
    </PubKeyValue>
    <SignatureVersion>A005</SignatureVersion>
  </SignaturePubKeyInfo>
  <PartnerID>'.$parameters->partner.'</PartnerID>
  <UserID>'.$parameters->user.'</UserID>
</SignaturePubKeyOrderData>';

$A005 = base64_encode(gzcompress($A005));


$xml = '<?xml version="1.0"?>
<ebicsUnsecuredRequest xmlns="http://www.ebics.org/H003" Revision="1" Version="H003">
  <header authenticate="true">
    <static>
      <HostID>'.$parameters->host.'</HostID>
      <PartnerID>'.$parameters->partner.'</PartnerID>
      <UserID>'.$parameters->user.'</UserID>
      <OrderDetails>
        <OrderType>INI</OrderType>
        <OrderID>A102</OrderID>
        <OrderAttribute>DZNNN</OrderAttribute>
      </OrderDetails>
      <SecurityMedium>0000</SecurityMedium>
    </static>
    <mutable/>
  </header>
  <body>
    <DataTransfer>
      <OrderData>'.$A005.'</OrderData>
    </DataTransfer>
  </body>
</ebicsUnsecuredRequest>';


$request = new Sabre\HTTP\Request('POST', $parameters->url);
$request->setBody($xml);

$client = new Sabre\HTTP\Client();
$client->addCurlSetting(CURLOPT_SSL_VERIFYPEER, false);

$response = $client->send($request);
echo $response->getBodyAsString();

