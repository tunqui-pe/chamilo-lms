<?php

/**
 *
 * @package chamilo.plugin.buycourses
 *
 */

require_once '../../config.php';
use ChamiloSession as Session;
use Transbank\Webpay\Configuration;
use Transbank\Webpay\Webpay;

Session::read('_user');
$plugin = BuyCoursesPlugin::create();
$transkbankParams = $plugin->getTransbankParams();
$configuration = new Configuration();

if((int)$transkbankParams['integration'] == 1){
    $transaction = (new Webpay(Configuration::forTestingWebpayPlusNormal()))->getNormalTransaction();
} else {
    $commerceCode = $transkbankParams['commerce_code'];
    $privateKeyWebPay = $transkbankParams['private_key'];
    $publicCertWebPay = $transkbankParams['public_cert'];
    $webPayCert = $configuration->getWebpayCert();

    /*$webPayCert = "-----BEGIN CERTIFICATE-----\n" .
        "MIIDizCCAnOgAwIBAgIJAIXzFTyfjyBkMA0GCSqGSIb3DQEBCwUAMFwxCzAJBgNV\n" .
        "BAYTAkNMMQswCQYDVQQIDAJSTTERMA8GA1UEBwwIU2FudGlhZ28xEjAQBgNVBAoM\n" .
        "CXRyYW5zYmFuazEMMAoGA1UECwwDUFJEMQswCQYDVQQDDAIxMDAeFw0xODAzMjkx\n" .
        "NjA4MjhaFw0yMzAzMjgxNjA4MjhaMFwxCzAJBgNVBAYTAkNMMQswCQYDVQQIDAJS\n" .
        "TTERMA8GA1UEBwwIU2FudGlhZ28xEjAQBgNVBAoMCXRyYW5zYmFuazEMMAoGA1UE\n" .
        "CwwDUFJEMQswCQYDVQQDDAIxMDCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoC\n" .
        "ggEBAKRqDk/pv8GeWnEaTVhfw55fThmqbFZOHEc/Un7oVWP+ExjD0kZ/aAwMJZ3d\n" .
        "9hpbBExftjoyJ0AYKJXA2CyLGxRp30LapBa2lMehzdP6tC5nrCYbDFz8r8ZyN/ie\n" .
        "4lBQ8GjfONq34cLQfM+tOxyazgDYRnZVD9tvOcqI5bFwFKqpn/yMr9Eya7gTo/OP\n" .
        "wyz69sAF8MKr0YN941n6C1Cdrzp6cRftdj83nlI75Ue//rMYih/uQYiht4XWFjAA\n" .
        "usoOG/IVVCCHhVQGE/Rp22dAF8JzWYZWCe+ICOKjEzEZPjDBqPoh9O+0eGTFVwn2\n" .
        "qZf2iSLDKBOiha1wwzpTiiJV368CAwEAAaNQME4wHQYDVR0OBBYEFDfN1Tlj7wbn\n" .
        "JIemBNO1XrUOikQpMB8GA1UdIwQYMBaAFDfN1Tlj7wbnJIemBNO1XrUOikQpMAwG\n" .
        "A1UdEwQFMAMBAf8wDQYJKoZIhvcNAQELBQADggEBACzXPSHet7aZrQvMUN03jOqq\n" .
        "w37brCWZ+L/+pbdOugVRAQRb2W+Z6gyrJ2BuUuiZLCXpjvXACSpwcSB3JesWs9KE\n" .
        "YO8E8ofF7a6ORvi2Mw0vpBbwJLqnci1gVlAj3X8r/VbX2rGbvRy+BJAF769xr43X\n" .
        "dtns0JIWwKud0xC3iRPMnewo/75HIblbN3guePfouoR2VgfBmeU72UR8O+OpjwbF\n" .
        "vpidobGqTGvZtxRV5axer69WY0rAXRhTSfkvyGTXERCJ3vdsF/v9iNKHhERUnpV6\n" .
        "KDrfvgD9uqWH12/89hfsfVN6iRH9UOE+SKoR/jHtvLMhVHpa80HVK1qdlfqUTZo=\n" .
        "-----END CERTIFICATE-----";*/

    $configuration->setEnvironment(Webpay::PRODUCCION);
    $configuration->setCommerceCode($commerceCode);
    $configuration->setPrivateKey($privateKeyWebPay);
    $configuration->setPublicCert($publicCertWebPay);
    $configuration->setWebpayCert($webPayCert);

    $transaction = (new Webpay($configuration))->getNormalTransaction();
}

$tokenWS = filter_input(INPUT_POST, 'token_ws');
$result = $transaction->getTransactionResult($tokenWS);
$output = $result->detailOutput;


$form = new FormValidator(
    'return-form',
    'post',
    $result->urlRedirection
);

if($output->responseCode == 0){

    $byOrderReference = $output->buyOrder;
    $sale = $plugin->getSaleReference($byOrderReference);

    echo '<script>window.localStorage.clear();</script>';
    echo '<script>window.localStorage.setItem("authorizationCode","'.$output->authorizationCode.'");</script>';
    echo '<script>window.localStorage.setItem("amount","'.$output->amount.'");</script>';
    echo '<script>window.localStorage.setItem("responseCode", "'.$output->responseCode.'");</script>';

    $plugin->completeSale($sale['id']);
    $form->addHidden('response',$output->responseCode);
    $form->addHidden('token_ws',$tokenWS);
    $form->display();

} else {

    $byOrderReference = $output->buyOrder;
    $sale = $plugin->getSaleReference($byOrderReference);
    $plugin->cancelSale($sale['id']);
    $form->addHidden('response',$output->responseCode);
    $form->addHidden('token_ws',$tokenWS);
    $form->display();
}

echo '
        <script>
            document.getElementById("return-form").submit();
        </script>
    ';

