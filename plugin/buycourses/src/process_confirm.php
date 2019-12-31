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
            //$commerceCode = $transkbankParams['commerce_code'];
            //$privateKeyWebPay = $transkbankParams['private_key'];
            //$publicCertWebPay = $transkbankParams['public_cert'];
            $commerceCode = 597035029575;
            $privateKeyWebPay =  "-----BEGIN RSA PRIVATE KEY-----\n" .
                "MIIEpQIBAAKCAQEArcp4JmNB7uKtZSR98AdXwaFiLN4wUBSC6HjLWZYYNhlcN81f\n" .
                "tWfOQbsYDT5rQofEMi87F6kqp9gfYdypqW0ydkHwNqGqQZYE5QeHykXW7fAhhQr3\n" .
                "uM4HIIcNZ6gjZwwMIUWlUJ7SVvLbE9Y2OhHHkHolTzDcRZNx0WOW123aVUWPbeLp\n" .
                "dSOOtrHZkhDlPgBNT+Y6QI7aUsLxMpy6/mbX68/UL3k7lN1CYJvWpcPYC4NHmbro\n" .
                "vsAc+m1lv4B245yS72YoPC/YzpOZEQJWOzWb9ysvsHJCuTTRjt9ewwC9KnPzbm3y\n" .
                "fxTTq+86vlhzLftQd1EGMC6jp9v6lXYm/cTmuQIDAQABAoIBAFmvDmng9vlsCMcV\n" .
                "GdhwMZ0+xwcYch4hN5z4GRhWGJBybeBuH2Hh+9J31mWfPILMxTaQoRIIvnZ3VVqP\n" .
                "IQ7Jxyy3wlqu+sl1vXjjdfOwLsYDEYkyq7u2tn7Wstg3MG3RDcFty23bR/iOwvdt\n" .
                "p/Y3jqDEJ9TCBinxeN7xYboyemVd8jYxgPDOrnC8xSweTI0RSFE/qQj+PgwUOS+r\n" .
                "NLb5ArZhpTlslynGOBJIZdY0Tx3grZVl7DVh8O3dzRggIKePX/GjFmt64OIjQkfZ\n" .
                "QTSwhUJ1V3KE2R9cPjhG/fpPXxUhva3lGkT9nCxbcGPzFngoHiMbD1bQTabFyvCR\n" .
                "3PcK3rECgYEA3gRG5N27RlCl0BKWkJvB7RftEW4CQ6O+ehMrbN+jCu60BW5g9xQU\n" .
                "dS6KYrKM1+sJEeuD0bYjXiWq6tdAFBqvkgy60Yv28V3cUtC2gcn+OLBj5wq8e9pC\n" .
                "XA4+S7tOop6skJWTZPHgWEuFwRLIMgfjYNHULHZqInRWYSX47DPm788CgYEAyGR2\n" .
                "35rFZKb/ylTZuzhgypie4jWYArpif8C68C53Tk92QDZ3+MKRYQI5uMCLKcXTp6GI\n" .
                "UEOjVBpRVtylU540Ec2aaa74l0lxwMrxQrMmDdcAvKSl6fYhMjiO+zjEG8iZ22qm\n" .
                "t0I83DDJqaKTLFLDn/21ZCRowk5Ksh/aJBp/mvcCgYEAhGgOk729S5EbYn1DeFi8\n" .
                "Dhdf1i5CgW5tUcaO9m5lgUac7ERI5fH+xWgNNhGAN+E/VoWb5vz2GNbkGQxoddT4\n" .
                "cmTPIduUWZx9opJA1iTOTaa83fSkNkUToG0KMAY0Pn3dMplR/zjVcDMSQPRfmp7j\n" .
                "t0FMhXvVjljNj0CjNNM7XjcCgYEAtDb1WN5dislM3G82aFKpaUmcCZ9dF4b7Mhzn\n" .
                "gTyoerqng9P4TLTd/Tn9IxO1k1mfoO96IYTSi74nTFCeNfbeqOwYY9bBJX0dWd6o\n" .
                "3e0y2dtzhDMmKqP48qs+mttQMhRTmanuSHy/Mt6FXZMhfAjqeSW3TVoZBDVYo5xV\n" .
                "l4ZlH70CgYEArS5eB85P3MJjtI6YsgL91Ac27/XemIqBljsVL2r7cn6E8LvYgXJ2\n" .
                "l3U9Kuh4XimS8GmtgEWP7kZhsE/BPcbuIIs+3Kg6pL/H3taI667s6IAR13nbBGq9\n" .
                "GX0NxWs9RFsQ168lu73cs3uC5L0x0CuDpadycY9TzZutzZ4UKnuSL9k=\n" .
                "-----END RSA PRIVATE KEY-----";

            $publicCertWebPay = "-----BEGIN CERTIFICATE-----\n" .
                "MIIDZTCCAk0CFF15Yxz0sHS4KrhYsTyYBAHMWACsMA0GCSqGSIb3DQEBCwUAMG8x\n" .
                "CzAJBgNVBAYTAkNMMRMwEQYDVQQIDApTb21lLVN0YXRlMREwDwYDVQQHDAhTQU5U\n" .
                "SUFHTzEhMB8GA1UECgwYSW50ZXJuZXQgV2lkZ2l0cyBQdHkgTHRkMRUwEwYDVQQD\n" .
                "DAw1OTcwMzUwMjk1NzUwHhcNMTkxMjMxMDI0MDU3WhcNMjMxMjMwMDI0MDU3WjBv\n" .
                "MQswCQYDVQQGEwJDTDETMBEGA1UECAwKU29tZS1TdGF0ZTERMA8GA1UEBwwIU0FO\n" .
                "VElBR08xITAfBgNVBAoMGEludGVybmV0IFdpZGdpdHMgUHR5IEx0ZDEVMBMGA1UE\n" .
                "AwwMNTk3MDM1MDI5NTc1MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA\n" .
                "rcp4JmNB7uKtZSR98AdXwaFiLN4wUBSC6HjLWZYYNhlcN81ftWfOQbsYDT5rQofE\n" .
                "Mi87F6kqp9gfYdypqW0ydkHwNqGqQZYE5QeHykXW7fAhhQr3uM4HIIcNZ6gjZwwM\n" .
                "IUWlUJ7SVvLbE9Y2OhHHkHolTzDcRZNx0WOW123aVUWPbeLpdSOOtrHZkhDlPgBN\n" .
                "T+Y6QI7aUsLxMpy6/mbX68/UL3k7lN1CYJvWpcPYC4NHmbrovsAc+m1lv4B245yS\n" .
                "72YoPC/YzpOZEQJWOzWb9ysvsHJCuTTRjt9ewwC9KnPzbm3yfxTTq+86vlhzLftQ\n" .
                "d1EGMC6jp9v6lXYm/cTmuQIDAQABMA0GCSqGSIb3DQEBCwUAA4IBAQAtfcr2zPmT\n" .
                "e0SINv894McDGrIEw1lyr/yPkwvh0EKfXChTYHNoNej31+jsXjfK/I762jgnfLmy\n" .
                "1YGbaHkNGmnukF5Oaf/Sxrtp1ZfF1FdXDpU4NAmJmezCvERwMDheL9rEhBqX0fcs\n" .
                "05bl0eB0o4jnnZA6SC+vG/4GDb8f1UMcVEOOccJktJqXRajgTtsqKYOS3ProkmuY\n" .
                "ZNw5OYiJzrLh/VoBFGmq4M0Vw7jNFOqrBnyIM9nprfpiEGvYD1t+7f51poXsi9VK\n" .
                "PTBIF8zePsAi4l/N5kvc2nAyeyy3+/9zv2uUg+cGIbFIKHmi2hAoZzncWTIEoofK\n" .
                "tbYHiETFl6Qf\n".
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
