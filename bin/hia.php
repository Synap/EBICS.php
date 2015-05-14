<?php
require_once 'vendor/autoload.php';

$parameters = json_decode(file_get_contents('parameters.json'));

$X002 = '-----BEGIN CERTIFICATE-----
MIIDQzCCAiugAwIBAgIBATANBgkqhkiG9w0BAQsFADBlMQswCQYDVQQGEwJGUjEO
MAwGA1UECgwFU3luYXAxFDASBgNVBAcMC01vbnRwZWxsaWVyMQ8wDQYDVQQIDAZG
cmFuY2UxHzAdBgkqhkiG9w0BCQEWEGNvbnRhY3RAc3luYXAuZnIwHhcNMTUwNDA4
MjIyNDAwWhcNMjAwNDA4MjIyNDUwWjBlMQswCQYDVQQGEwJGUjEOMAwGA1UECgwF
U3luYXAxFDASBgNVBAcMC01vbnRwZWxsaWVyMQ8wDQYDVQQIDAZGcmFuY2UxHzAd
BgkqhkiG9w0BCQEWEGNvbnRhY3RAc3luYXAuZnIwggEiMA0GCSqGSIb3DQEBAQUA
A4IBDwAwggEKAoIBAQCVLWvq5Li2WwN3ZrvEkwc6FoGELhP9TxOEHLRudpf2yijh
UCnHbM3xAGCIhYXB5r3TtnVZQo88U5UbH81nd0iXgHuJXnFuHA0YSegn2mRH+zTm
Uz9zXid/+4ScvJ6mR0HlCPYZSX34xVtKSCnLA+s//07XLihAC9bd++oK9BPcjSPP
mcZIExQXePWlZqpPkfPpnGk/6jpXgygqZWkjIdVF+r4p54X24POqeYnoguG/3L+D
8cnSlMWHs28oRJ4jT9NX9X3VjvlkDwjln+L6VTv8VSWjxq1YKvmN4EvKADsXVbVw
UBXDSV427mNTZzvlqWX8jQKjfRvO2JIRz9D8u4dbAgMBAAEwDQYJKoZIhvcNAQEL
BQADggEBABPzNzAcDPaf5Ii3Z8NoCo/kftegVNRQSADpH8Y+5XERB+XqxVdlxxxb
G/ozpRh97bNWedL0ncLs2rF8Os9R6Xi3z1Hge4ez/F314DZ6T6zsDvvSaT5IjbN+
VtFlzIwoyc185c6YqKso3rr5qSSWK0hBnsFuqCu4h6y2ikrpQSZPu+mZoKfMkA6x
yvIOkvbS9Wv8oIY1+qgyyjlfAsqGupMgKBbOqTCiK1V+cAZ43r5zDEJhRmqZNBUv
fCpRWo8PrC8F7fDkSagkLxVzRiTpD1HSSeyTsMDTMY3LbyLPAA8X9bymvW0NUlpV
4i9ZvscqYm21dFr9S4lGzBSdIiw2R/c=
-----END CERTIFICATE-----';

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

$E002 = '-----BEGIN CERTIFICATE-----
MIIDQzCCAiugAwIBAgIBATANBgkqhkiG9w0BAQsFADBlMQswCQYDVQQGEwJGUjEO
MAwGA1UECgwFU3luYXAxFDASBgNVBAcMC01vbnRwZWxsaWVyMQ8wDQYDVQQIDAZG
cmFuY2UxHzAdBgkqhkiG9w0BCQEWEGNvbnRhY3RAc3luYXAuZnIwHhcNMTUwNDA4
MjIyNDAwWhcNMjAwNDA4MjIyNDUwWjBlMQswCQYDVQQGEwJGUjEOMAwGA1UECgwF
U3luYXAxFDASBgNVBAcMC01vbnRwZWxsaWVyMQ8wDQYDVQQIDAZGcmFuY2UxHzAd
BgkqhkiG9w0BCQEWEGNvbnRhY3RAc3luYXAuZnIwggEiMA0GCSqGSIb3DQEBAQUA
A4IBDwAwggEKAoIBAQCVLWvq5Li2WwN3ZrvEkwc6FoGELhP9TxOEHLRudpf2yijh
UCnHbM3xAGCIhYXB5r3TtnVZQo88U5UbH81nd0iXgHuJXnFuHA0YSegn2mRH+zTm
Uz9zXid/+4ScvJ6mR0HlCPYZSX34xVtKSCnLA+s//07XLihAC9bd++oK9BPcjSPP
mcZIExQXePWlZqpPkfPpnGk/6jpXgygqZWkjIdVF+r4p54X24POqeYnoguG/3L+D
8cnSlMWHs28oRJ4jT9NX9X3VjvlkDwjln+L6VTv8VSWjxq1YKvmN4EvKADsXVbVw
UBXDSV427mNTZzvlqWX8jQKjfRvO2JIRz9D8u4dbAgMBAAEwDQYJKoZIhvcNAQEL
BQADggEBABPzNzAcDPaf5Ii3Z8NoCo/kftegVNRQSADpH8Y+5XERB+XqxVdlxxxb
G/ozpRh97bNWedL0ncLs2rF8Os9R6Xi3z1Hge4ez/F314DZ6T6zsDvvSaT5IjbN+
VtFlzIwoyc185c6YqKso3rr5qSSWK0hBnsFuqCu4h6y2ikrpQSZPu+mZoKfMkA6x
yvIOkvbS9Wv8oIY1+qgyyjlfAsqGupMgKBbOqTCiK1V+cAZ43r5zDEJhRmqZNBUv
fCpRWo8PrC8F7fDkSagkLxVzRiTpD1HSSeyTsMDTMY3LbyLPAA8X9bymvW0NUlpV
4i9ZvscqYm21dFr9S4lGzBSdIiw2R/c=
-----END CERTIFICATE-----';

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


$request = new Sabre\HTTP\Request('POST', $parameters->url);
$request->setBody($xml);

$client = new Sabre\HTTP\Client();
$client->addCurlSetting(CURLOPT_SSL_VERIFYPEER, false);

$response = $client->send($request);
echo $response->getBodyAsString();

