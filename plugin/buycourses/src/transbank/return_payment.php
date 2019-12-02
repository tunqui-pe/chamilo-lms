<?php

require_once '../../config.php';

use Transbank\Webpay\Configuration;
use Transbank\Webpay\Webpay;

$transaction = (new Webpay(Configuration::forTestingWebpayPlusNormal()))->getNormalTransaction();
$tokenWS = filter_input(INPUT_POST, 'token_ws');
$result = $transaction->getTransactionResult($tokenWS);
$output = $result->detailOutput;
$plugin = BuyCoursesPlugin::create();

if($output->responseCode == 0){

    $byOrderReference = $output->buyOrder;
    $sale = $plugin->getSaleReference($byOrderReference);

    echo '<script>window.localStorage.clear();</script>';
    echo '<script>window.localStorage.setItem("authorizationCode","'.$output->authorizationCode.'");</script>';
    echo '<script>window.localStorage.setItem("amount","'.$output->amount.'");</script>';
    echo '<script>window.localStorage.setItem("responseCode", "'.$output->responseCode.'");</script>';

    $plugin->completeSale($sale['id']);

    $form = new FormValidator(
        'return-form',
        'post',
        $result->urlRedirection
    );
    $form->addHidden('response',$output->responseCode);
    $form->addHidden('token_ws',$tokenWS);
    $form->display();

    echo '
        <script>
            document.getElementById("return-form").submit();
        </script>
    ';
} else {

    $byOrderReference = $output->buyOrder;
    $sale = $plugin->getSaleReference($byOrderReference);
    $plugin->cancelSale($sale['id']);

    $form = new FormValidator(
        'return-form',
        'post',
        $result->urlRedirection
    );

    $form->addHidden('response',$output->responseCode);
    $form->addHidden('token_ws',$tokenWS);
    $form->display();

    echo '
        <script>
            document.getElementById("return-form").submit();
        </script>
    ';

}


