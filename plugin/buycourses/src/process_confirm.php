<?php
/* For license terms, see /license.txt */

/**
 * Process purchase confirmation script for the Buy Courses plugin.
 *
 * @package chamilo.plugin.buycourses
 */

require_once '../config.php';

use Transbank\Webpay\Configuration;
use Transbank\Webpay\Webpay;


$plugin = BuyCoursesPlugin::create();

$saleId = $_SESSION['bc_sale_id'];

if (empty($saleId)) {
    api_not_allowed(true);
}

$sale = $plugin->getSale($saleId);

$userInfo = api_get_user_info($sale['user_id']);

if (empty($sale)) {
    api_not_allowed(true);
}

$currency = $plugin->getCurrency($sale['currency_id']);
$globalParameters = $plugin->getGlobalParameters();

switch ($sale['payment_type']) {
    case BuyCoursesPlugin::PAYMENT_TYPE_PAYPAL:
        $paypalParams = $plugin->getPaypalParams();

        $pruebas = $paypalParams['sandbox'] == 1;
        $paypalUsername = $paypalParams['username'];
        $paypalPassword = $paypalParams['password'];
        $paypalSignature = $paypalParams['signature'];

        require_once "paypalfunctions.php";

        $i = 0;
        $extra = "&L_PAYMENTREQUEST_0_NAME0={$sale['product_name']}";
        $extra .= "&L_PAYMENTREQUEST_0_AMT0={$sale['price']}";
        $extra .= "&L_PAYMENTREQUEST_0_QTY0=1";

        $expressCheckout = CallShortcutExpressCheckout(
            $sale['price'],
            $currency['iso_code'],
            'paypal',
            api_get_path(WEB_PLUGIN_PATH).'buycourses/src/success.php',
            api_get_path(WEB_PLUGIN_PATH).'buycourses/src/error.php',
            $extra
        );

        if ($expressCheckout["ACK"] !== 'Success') {
            $erroMessage = vsprintf(
                $plugin->get_lang('ErrorOccurred'),
                [$expressCheckout['L_ERRORCODE0'], $expressCheckout['L_LONGMESSAGE0']]
            );
            Display::addFlash(
                Display::return_message($erroMessage, 'error', false)
            );
            header('Location: ../index.php');
            exit;
        }

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
                ]
            );

            api_mail_html(
                '',
                $globalParameters['sale_email'],
                $plugin->get_lang('bc_subject'),
                $messageConfirmTemplate->fetch('buycourses/view/message_confirm.tpl')
            );
        }

        RedirectToPayPal($expressCheckout["TOKEN"]);
        break;
    case BuyCoursesPlugin::PAYMENT_TYPE_TRANSFER:
        $buyingCourse = false;
        $buyingSession = false;

        switch ($sale['product_type']) {
            case BuyCoursesPlugin::PRODUCT_TYPE_COURSE:
                $buyingCourse = true;
                $course = $plugin->getCourseInfo($sale['product_id']);
                break;
            case BuyCoursesPlugin::PRODUCT_TYPE_SESSION:
                $buyingSession = true;
                $session = $plugin->getSessionInfo($sale['product_id']);
                break;
        }

        $transferAccounts = $plugin->getTransferAccounts();

        $form = new FormValidator(
            'success',
            'POST',
            api_get_self(),
            null,
            null,
            FormValidator::LAYOUT_INLINE
        );

        if ($form->validate()) {
            $formValues = $form->getSubmitValues();

            if (isset($formValues['cancel'])) {
                $plugin->cancelSale($sale['id']);

                unset($_SESSION['bc_sale_id']);

                header('Location: '.api_get_path(WEB_PLUGIN_PATH).'buycourses/index.php');
                exit;
            }

            $messageTemplate = new Template();
            $messageTemplate->assign('user', $userInfo);
            $messageTemplate->assign(
                'sale',
                [
                    'date' => $sale['date'],
                    'product' => $sale['product_name'],
                    'currency' => $currency['iso_code'],
                    'price' => $sale['price'],
                    'reference' => $sale['reference'],
                ]
            );
            $messageTemplate->assign('transfer_accounts', $transferAccounts);

            api_mail_html(
                $userInfo['complete_name'],
                $userInfo['email'],
                $plugin->get_lang('bc_subject'),
                $messageTemplate->fetch('buycourses/view/message_transfer.tpl')
            );

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
                    ]
                );

                api_mail_html(
                    '',
                    $globalParameters['sale_email'],
                    $plugin->get_lang('bc_subject'),
                    $messageConfirmTemplate->fetch('buycourses/view/message_confirm.tpl')
                );
            }

            Display::addFlash(
                Display::return_message(
                    sprintf(
                        $plugin->get_lang('PurchaseStatusX'),
                        $plugin->get_lang('PendingReasonByTransfer')
                    ),
                    'success',
                    false
                )
            );

            unset($_SESSION['bc_sale_id']);
            header('Location: '.api_get_path(WEB_PLUGIN_PATH).'buycourses/src/session_catalog.php');
            exit;
        }

        $form->addButton(
            'confirm',
            $plugin->get_lang('ConfirmOrder'),
            'check',
            'success',
            'default',
            null,
            ['id' => 'confirm']
        );
        $form->addButtonCancel($plugin->get_lang('CancelOrder'), 'cancel');

        $template = new Template();

        if ($buyingCourse) {
            $template->assign('course', $course);
        } elseif ($buyingSession) {
            $template->assign('session', $session);
        }

        $template->assign('buying_course', $buyingCourse);
        $template->assign('buying_session', $buyingSession);
        $template->assign('terms', $globalParameters['terms_and_conditions']);
        $template->assign('title', $sale['product_name']);
        $template->assign('price', $sale['price']);
        $template->assign('currency', $sale['currency_id']);
        $template->assign('user', $userInfo);
        $template->assign('transfer_accounts', $transferAccounts);
        $template->assign('form', $form->returnForm());
        $template->assign('is_bank_transfer', true);

        $content = $template->fetch('buycourses/view/process_confirm.tpl');

        $template->assign('content', $content);
        $template->display_one_col_template();
        break;
    case BuyCoursesPlugin::PAYMENT_TYPE_CULQI:
        // We need to include the main online script, acording to the Culqi documentation the JS needs to be loeaded
        // directly from the main url "https://integ-pago.culqi.com" because a local copy of this JS is not supported
        $htmlHeadXtra[] = '<script src="//integ-pago.culqi.com/js/v1"></script>';

        $buyingCourse = false;
        $buyingSession = false;

        switch ($sale['product_type']) {
            case BuyCoursesPlugin::PRODUCT_TYPE_COURSE:
                $buyingCourse = true;
                $course = $plugin->getCourseInfo($sale['product_id']);
                break;
            case BuyCoursesPlugin::PRODUCT_TYPE_SESSION:
                $buyingSession = true;
                $session = $plugin->getSessionInfo($sale['product_id']);
                break;
        }

        $form = new FormValidator(
            'success',
            'POST',
            api_get_self(),
            null,
            null,
            FormValidator::LAYOUT_INLINE
        );

        if ($form->validate()) {
            $formValues = $form->getSubmitValues();

            if (isset($formValues['cancel'])) {
                $plugin->cancelSale($sale['id']);

                unset($_SESSION['bc_sale_id']);

                Display::addFlash(
                    Display::return_message(
                        $plugin->get_lang('OrderCanceled'),
                        'warning',
                        false
                    )
                );

                header('Location: '.api_get_path(WEB_PLUGIN_PATH).'buycourses/index.php');
                exit;
            }
        }
        $form->addButton(
            'confirm',
            $plugin->get_lang('ConfirmOrder'),
            'check',
            'success',
            'default',
            null,
            ['id' => 'confirm']
        );
        $form->addButton(
            'cancel',
            $plugin->get_lang('CancelOrder'),
            'times',
            'danger',
            'default',
            null,
            ['id' => 'cancel']
        );

        $template = new Template();

        if ($buyingCourse) {
            $template->assign('course', $course);
        } elseif ($buyingSession) {
            $template->assign('session', $session);
        }

        $template->assign('buying_course', $buyingCourse);
        $template->assign('buying_session', $buyingSession);
        $template->assign('terms', $globalParameters['terms_and_conditions']);
        $template->assign('title', $sale['product_name']);
        $template->assign('price', floatval($sale['price']));
        $template->assign('currency', $plugin->getSelectedCurrency());
        $template->assign('user', $userInfo);
        $template->assign('sale', $sale);
        $template->assign('form', $form->returnForm());
        $template->assign('is_culqi_payment', true);
        $template->assign('culqi_params', $culqiParams = $plugin->getCulqiParams());

        $content = $template->fetch('buycourses/view/process_confirm.tpl');

        $template->assign('content', $content);
        $template->display_one_col_template();

    case BuyCoursesPlugin::PAYMENT_TYPE_TRANSBANK:

        $transkbankParams = $plugin->getTransbankParams();
        $htmlHeadXtra[] = '<link rel="stylesheet" type="text/css" href="'.api_get_path(
                WEB_PLUGIN_PATH
            ).'buycourses/resources/css/style.css"/>';
        $buyingCourse = false;
        $buyingSession = false;

        switch ($sale['product_type']) {
            case BuyCoursesPlugin::PRODUCT_TYPE_COURSE:
                $buyingCourse = true;
                $course = $plugin->getCourseInfo($sale['product_id']);
                break;
            case BuyCoursesPlugin::PRODUCT_TYPE_SESSION:
                $buyingSession = true;
                $session = $plugin->getSessionInfo($sale['product_id']);
                break;
        }

        $returnURL = api_get_path(WEB_PLUGIN_PATH).'buycourses/src/transbank/return_payment.php';
        $finalURL = api_get_path(WEB_PLUGIN_PATH).'buycourses/src/transbank/final_payment.php';

        $configuration = new Configuration();

        if((int)$transkbankParams['integration'] == 1){
            $configuration->setEnvironment(Webpay::TEST);
            $transaction = (new Webpay(Configuration::forTestingWebpayPlusNormal()))->getNormalTransaction();
        } else {
            $configuration->setEnvironment(Webpay::PRODUCCION);
            //We assign the trade code
            $commerceCode = $transkbankParams['commerce_code'];
            //$privateKeyWebPay = $transkbankParams['private_key'];
            //$publicCertWebPay = $transkbankParams['public_cert'];
            $privateKeyWebPay =  "-----BEGIN RSA PRIVATE KEY-----\n".
                "MIIEpQIBAAKCAQEAoyOCGPMCuig5l9cEWJK0SV2Xz4QXBYAgWr8gPvXUQnuD0AxT\n".
                "ldNZy4apMgPxE5n5obqNs5c09OSxhpOanDfK44ERySps2HVDfirNejWHL5APN/HX\n".
                "QPsho1dehN2Vv5y8A/T9typq+fu5DW1oxY2KgczGdk6N8P4FZob6jKCzUlCSsXNM\n".
                "L8Rm8zQa6Mm1cGr9mITtbPspW6ZHhWoJ7T3e5qBKaBefGIpV4Pia2i+5vZMLOWHM\n".
                "dqYFEq7js53U6Gx6Co0vLMFbY3JyoEPdY98VvkSXTerHkgdwED7baKhUDXGRCLJ4\n".
                "qrAhhBLm2jSXwlS8h8RAaS8UwHGgYVVqd5U/FwIDAQABAoIBAQCTiJUrAvnAaIhN\n".
                "LQKdJ28ruhyEaqx3KPwZlScQSTkANrtp9vvQTyaxzMJOnQnz9Bexjwh/FYuqvPdf\n".
                "ATWdeUden2b6SgfNaZ70Brl0f9wVw24/5dIIzhQJqAWumsXGV3QhD/ozH4VHNcKQ\n".
                "xhUuM2bDI/GHJFtxSgiQRBlp/BqvoQQi+i9kfT8gya+hC0fLWp2pLiha2uvPkePC\n".
                "AGCPswi1Ge4yksYZnxA45EfSrLWoiQ+rUeRzvwBl1KkquplgC4vcFl9q/eYhoZft\n".
                "EeU8rBtwDquq0WA52m9ghJX256/oKNG+kD0leSRQzLFiUPF5n1B1qUKExqfwLyug\n".
                "jHQIX9ohAoGBANdzQmsmAohD+a+Yh2araqR/0NaieJPrHfFwvdrsmhF+srBqdPlI\n".
                "fEY0nB2RjO/qJ00quChoBZArXikdtJTOooMsRmO6fUMjx98/40ljIBcLn4kVTall\n".
                "b5Cz+Xu/m/ucbBHHJQeUqPndOLvYJOM+UxXBLsyJ+zYCaBh/AZb2aIx7AoGBAMHX\n".
                "y3M+4fkXya6Y7sSBBX4JXKQmmh8UCf05KXf3ADNOy5o/bHZ0GWv8pb4hae1GGMrg\n".
                "ILNkdqlw0m9cuUmpOOuoIvsOnwnS9TC18dHYWHhUk8MTOIhdX49aY04FszdbF99A\n".
                "k48ooDmyxPEdl3ANIllDzafIJkCbWRO2M1MlllsVAoGARirjlsHqUTbSOr4SWv24\n".
                "3ZpDCaQgYQxR1DBDpOkpxEjfKVWCgy14S+UWcwrUO86mvhsLnx1BspJtODbUeSJT\n".
                "CyWARzqVUSh2D99exqfh6599dcfaYzEGBLqYphThWDC5gZC6Hp3r6nSB5aufV+MU\n".
                "bCFefH7zscNW46N5gRD/O8ECgYEAiJ59J/aT8anQXZwv/JMqudADWTZTvb+z4qMd\n".
                "FQ4jOAY6/bXhzgK5wCBK+Jw4OiEDbElXAti1wWphBlgFx2LbWUwhi2ycrqHeabxy\n".
                "eQHQKM8DbaPoXkPhC/oar2zZCRTM2G59EZMCimfy4jWG/FRldyCQm8Y3H6XdvETY\n".
                "G6wMMpkCgYEAlQflexC/PEJpgDXrz8van3s8uVodygxwyZIa8/6Lpzb+fVzI3Ovz\n".
                "uBYXxfB1jozVcEovt2/G5flthGpslh3frLZPjT+kA96cT5FKk9QoNsBhFfnyskeY\n".
                "jY3tCHjL8ZjDSJ7Jvf4Vdjd8MR/bR93qxjN9a9gH6Hdu0taW1G2lCJ0=\n".
                "-----END RSA PRIVATE KEY-----";

            $publicCertWebPay = "-----BEGIN CERTIFICATE-----\n".
                "MIIDUTCCAjkCFH6499HVMytWRm08jPaZlJfT8wQ8MA0GCSqGSIb3DQEBCwUAMGUx\n".
                "CzAJBgNVBAYTAkNMMRMwEQYDVQQIDApTb21lLVN0YXRlMREwDwYDVQQHDAhTQU5U\n".
                "SUFHTzEXMBUGA1UECgwORURVQ0FDSU9OQ0hJTEUxFTATBgNVBAMMDDU5NzAzNTAy\n".
                "OTU3NTAeFw0xOTExMTgwMTQyMjNaFw0yMzExMTcwMTQyMjNaMGUxCzAJBgNVBAYT\n".
                "AkNMMRMwEQYDVQQIDApTb21lLVN0YXRlMREwDwYDVQQHDAhTQU5USUFHTzEXMBUG\n".
                "A1UECgwORURVQ0FDSU9OQ0hJTEUxFTATBgNVBAMMDDU5NzAzNTAyOTU3NTCCASIw\n".
                "DQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAKMjghjzArooOZfXBFiStEldl8+E\n".
                "FwWAIFq/ID711EJ7g9AMU5XTWcuGqTID8ROZ+aG6jbOXNPTksYaTmpw3yuOBEckq\n".
                "bNh1Q34qzXo1hy+QDzfx10D7IaNXXoTdlb+cvAP0/bcqavn7uQ1taMWNioHMxnZO\n".
                "jfD+BWaG+oygs1JQkrFzTC/EZvM0GujJtXBq/ZiE7Wz7KVumR4VqCe093uagSmgX\n".
                "nxiKVeD4mtovub2TCzlhzHamBRKu47Od1OhsegqNLyzBW2NycqBD3WPfFb5El03q\n".
                "x5IHcBA+22ioVA1xkQiyeKqwIYQS5to0l8JUvIfEQGkvFMBxoGFVaneVPxcCAwEA\n".
                "ATANBgkqhkiG9w0BAQsFAAOCAQEAYgkXBvh8HiVDPFHRROyd3UClx1aJAmKa3/+P\n".
                "6Hx1qSvVy0BQB1m4BerFuDlMubGQnlmCpGhyHtXKHHkkGd4MYPaIJV5CF9F1pA57\n".
                "gQC8uxGh0lvK4sk0pj1cHf4QEkvrLz8oMPPff2c79ZECG7DvrpH+vH0fz3pPtcZ3\n".
                "xDKhIOtMHfja2/eAVLXU2KVxbmc6uJjvLfuO2syFalubENgx408JXomH6mmMQB0W\n".
                "9YuqCq14EPcaYN7i/YGz/zeykxgqpJgHuDNGE7DgbdUzrEClCqq3+WDSjT7GLnFf\n".
                "EEzJAdqtLOWc0gca1sngjYa1fP6cU+0aQ0USQHf1Bv9LCrxbMg==\n".
                "-----END CERTIFICATE-----";


            $configuration->setCommerceCode($commerceCode);
            $configuration->setPrivateKey($privateKeyWebPay);
            $configuration->setPublicCert($publicCertWebPay);

            $transaction = new Webpay($configuration);
        }

        $amount = floatval($sale['price']);
        $sessionID = $sale['reference'];
        $buyOrder = strval(rand(100000, 999999999));

        $initResult = $transaction->initTransaction(
            $amount,$sessionID,$buyOrder, $returnURL, $finalURL
        );

        $formAction = $initResult->url;
        $tokenWs = $initResult->token;

        $template = new Template();

        if ($buyingCourse) {
            $template->assign('course', $course);
        } elseif ($buyingSession) {
            $template->assign('session', $session);
        }
        $template->assign('buying_course', $buyingCourse);
        $template->assign('buying_session', $buyingSession);
        $template->assign('terms', $globalParameters['terms_and_conditions']);
        $template->assign('form_action', $formAction);
        $template->assign('amount', $amount);
        $template->assign('buy_order', $buyOrder);
        $template->assign('token_ws', $tokenWs);
        $content = $template->fetch('buycourses/view/transbank/process_transbank.tpl');

        $template->assign('content', $content);
        $template->display_one_col_template();

        break;
    case BuyCoursesPlugin::PAYMENT_TYPE_SERVIPAG:

        $buyingCourse = false;
        $buyingSession = false;
        $urlRedirect = null;
        $typePayment = null;

        switch ($sale['product_type']) {
            case BuyCoursesPlugin::PRODUCT_TYPE_COURSE:
                $buyingCourse = true;
                $course = $plugin->getCourseInfo($sale['product_id']);
                if($sale['payment_type'] == BuyCoursesPlugin::PAYMENT_TYPE_SERVIPAG){
                    $urlRedirect = $course['url_servipag'];
                    $typePayment = 'servipag';
                }

                break;
            case BuyCoursesPlugin::PRODUCT_TYPE_SESSION:
                $buyingSession = true;
                $session = $plugin->getSessionInfo($sale['product_id']);
                if($sale['payment_type'] == BuyCoursesPlugin::PAYMENT_TYPE_SERVIPAG){
                    $urlRedirect = $session['url_servipag'];
                    $typePayment = 'servipag';
                }
                break;
        }


        if(!empty($urlRedirect)){
            $htmlHeadXtra[] = '<meta http-equiv="refresh" content="2; url='.$urlRedirect.'">';
        }

        $template = new Template();

        if ($buyingCourse) {
            $template->assign('course', $course);
            $template->assign('type', $typePayment);
        } elseif ($buyingSession) {
            $template->assign('session', $session);
            $template->assign('type', $typePayment);
        }
        $template->assign('urlredirect', $urlRedirect);


        $content = $template->fetch('buycourses/view/process_redirect.tpl');

        $template->assign('content', $content);
        $template->display_one_col_template();

        break;
}
