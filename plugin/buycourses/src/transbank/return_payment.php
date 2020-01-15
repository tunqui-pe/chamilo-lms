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
$globalParameters = $plugin->getGlobalParameters();
$configuration = new Configuration();

if ((int)$transkbankParams['integration'] == 1) {
    $transaction = (new Webpay(Configuration::forTestingWebpayPlusNormal()))->getNormalTransaction();
} else {
    $commerceCode = $transkbankParams['commerce_code'];
    $privateKeyWebPay = $transkbankParams['private_key'];
    $publicCertWebPay = $transkbankParams['public_cert'];
    $webPayCert = $configuration->getWebpayCert();

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
$statusTransaction = $output->responseCode;

$form = new FormValidator(
    'return-form',
    'post',
    $result->urlRedirection
);

echo '<style type="text/css"> #return-form fieldset { display: none;}</style>';

if ($statusTransaction === 0) {
    $response = 1;
    $byOrderReference = $output->buyOrder;
    $cardNumber = $result->cardDetail->cardNumber;
    $transactionDate = $result->transactionDate;
    $authorizationCode = $result->detailOutput->authorizationCode;
    $paymentTypeCode = $result->detailOutput->paymentTypeCode;

    $sale = $plugin->getSaleReference($byOrderReference);
    $currency = $plugin->getCurrency($sale['currency_id']);
    $userInfo = api_get_user_info($sale['user_id']);

    echo '<script>window.localStorage.clear();</script>';
    echo '<script>window.localStorage.setItem("authorizationCode","'.$output->authorizationCode.'");</script>';
    echo '<script>window.localStorage.setItem("amount","'.$output->amount.'");</script>';
    echo '<script>window.localStorage.setItem("responseCode", "'.$output->responseCode.'");</script>';


    $plugin->completeSale($sale['id']);

    $paymentTypeTransbank = [
        'VD' => 'Venta Débito',
        'VN' => 'Venta Normal',
        'VC' => 'Venta en cuotas',
        'SI' => '3 cuotas sin interés',
        'S2' => '2 cuotas sin interés',
        'NC' => 'N Cuotas sin interés',
        'VP' => 'Venta Prepago',
    ];

    //Email Confirmation.

    if (!empty($globalParameters['sale_email'])) {
        $messageConfirmTemplate = new Template();
        $messageConfirmTemplate->assign('user', $userInfo);
        $messageConfirmTemplate->assign(
            'sale',
            [
                'date' => $sale['date'],
                'product' => $sale['product_name'],
                'currency' => $currency['iso_code'],
                'price' => $sale['price'],
                'reference' => $sale['reference'],
                'card_number' => $cardNumber,
                'transaction_date' => $transactionDate,
                'code_auth' => $authorizationCode,
                'payment_type' => $paymentTypeTransbank[$paymentTypeCode],
            ]
        );

        api_mail_html(
            '',
            [$globalParameters['sale_email'], $userInfo['email']],
            $plugin->get_lang('bc_subject'),
            $messageConfirmTemplate->fetch('buycourses/view/transbank/message_confirm_transbank.tpl')
        );
    }
    $form->addHidden('status', $response);

} else {
    $response = 2;
    $byOrderReference = $output->buyOrder;
    $sale = $plugin->getSaleReference($byOrderReference);
    $plugin->cancelSale($sale['id']);
    $form->addHidden('status', $response);
    $form->addHidden('token_ws', $tokenWS);
}

$form->display();

echo '
        <script>
            document.getElementById("return-form").submit();
        </script>
    ';

