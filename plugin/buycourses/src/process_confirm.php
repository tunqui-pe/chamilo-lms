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

        $configuration = new \Transbank\Webpay\Configuration();

        if((int)$transkbankParams['integration'] == 1){
            $configuration->setEnvironment(Webpay::TEST);
            $transaction = (new Webpay(Configuration::forTestingWebpayPlusNormal()))->getNormalTransaction();
        } else {
            $configuration->setEnvironment("PRODUCCION");
            $configuration->setCommerceCode(597035029575);

            $publicCertWebPay = "-----BEGIN CERTIFICATE-----\n" .
            "MIIDNDCCAhwCCQCu51zD0AshITANBgkqhkiG9w0BAQsFADBcMQswCQYDVQQGEwJB\n" .
            "VTETMBEGA1UECAwKU29tZS1TdGF0ZTEhMB8GA1UECgwYSW50ZXJuZXQgV2lkZ2l0\n" .
            "cyBQdHkgTHRkMRUwEwYDVQQDDAw1OTcwMzUwMjk1NzUwHhcNMjAwMTA3MjExNzE4\n" .
            "WhcNMjQwMTA2MjExNzE4WjBcMQswCQYDVQQGEwJBVTETMBEGA1UECAwKU29tZS1T\n" .
            "dGF0ZTEhMB8GA1UECgwYSW50ZXJuZXQgV2lkZ2l0cyBQdHkgTHRkMRUwEwYDVQQD\n" .
            "DAw1OTcwMzUwMjk1NzUwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDe\n" .
            "RGiUGpKOu57Ee7ozxz/3eCXsRSaRLJsHfk42b4ACnoIEUZ9n8tf6kR0n/YAYAUa2\n" .
            "GU0eKGRvHJagCuTKdA4SoctzSLFlTiuy9NIeIcVkf2AH38xFkJnamw8QwZOdcKJL\n" .
            "yd72Y9U3zjJyFkqJ8476eOukF08GH1AChFaj2AFan0p5ZckgWlmxdL6gXcT7mENg\n" .
            "Dv/E3zC01FDmCv8LwuMMkvhDCG4eJeg3PDUU9IgcpLH1n0SXUTvG+iksY72Gcz1q\n" .
            "OXONY3AgBjcUkbM9n/jEsWms2JVf++IKBxndkKr5iUVBN+LqzpKwVAhrC06j8+oq\n" .
            "dw/1BwgXziuIGD4fF/PBAgMBAAEwDQYJKoZIhvcNAQELBQADggEBADwAF3PGIRho\n" .
            "c14gldQGC2+zITBA91vIJigTZF37No0B6Qe86diRvuHaLFQg91cDNEnEu42TR693\n" .
            "0doprMxU+jZ5HNKQN7+arw8/mLNd1XQsvMOn0lBWDTQ/jG4I59hVZtbH7kCy8QPv\n" .
            "0kMajf62ET3EjxowV27Q1+gThezy5A18yNmz1yc1P50JBjj5auvqWpo7+OyLe+9M\n" .
            "NWi+V1XRf9W55mUq3VO72qFXfyS8sxnZ/ih4KVOHav+HTmFg5w5TCYQAWJxTOxp6\n" .
            "mmZvRNWwJY1iDOkWmPjXcVkePqJ91wv5xmYiLea72SO9qjftC+EeooRggGjbGvGu\n" .
            "KwcA6l8WaH4=\n" .
            "-----END CERTIFICATE-----";

            $privateKeyWebPay =  "-----BEGIN CERTIFICATE REQUEST-----\n" .
            "MIICoTCCAYkCAQAwXDELMAkGA1UEBhMCQVUxEzARBgNVBAgMClNvbWUtU3RhdGUx\n" .
            "ITAfBgNVBAoMGEludGVybmV0IFdpZGdpdHMgUHR5IEx0ZDEVMBMGA1UEAwwMNTk3\n" .
            "MDM1MDI5NTc1MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA3kRolBqS\n" .
            "jruexHu6M8c/93gl7EUmkSybB35ONm+AAp6CBFGfZ/LX+pEdJ/2AGAFGthlNHihk\n" .
            "bxyWoArkynQOEqHLc0ixZU4rsvTSHiHFZH9gB9/MRZCZ2psPEMGTnXCiS8ne9mPV\n" .
            "N84ychZKifOO+njrpBdPBh9QAoRWo9gBWp9KeWXJIFpZsXS+oF3E+5hDYA7/xN8w\n" .
            "tNRQ5gr/C8LjDJL4QwhuHiXoNzw1FPSIHKSx9Z9El1E7xvopLGO9hnM9ajlzjWNw\n" .
            "IAY3FJGzPZ/4xLFprNiVX/viCgcZ3ZCq+YlFQTfi6s6SsFQIawtOo/PqKncP9QcI\n" .
            "F84riBg+HxfzwQIDAQABoAAwDQYJKoZIhvcNAQELBQADggEBAKDAu/i4/rPKGMPY\n" .
            "2qFABharidx63BfK4eW5MN5CNWT9NdSexV84OxNsUbnZGkY2NnTFGMs7Sr2RI7uq\n" .
            "EmSwKbdon09J5VYE0LtDP9oiHrAD1dyO4ZlpYRw6+AD59pWA4ez4mxCBW6CxBYuc\n" .
            "qlJLmJuy7alGIEtdZKHPt+i4SBJcy7F+ksy4rlD6/yzGo+ksSH79UljHr3K+PPlK\n" .
            "VtCo6IwsvT1CDZDhRt1v5q1gyRvNA3IfbpxR1jJ/fL9s/ZS273/cZvGLmSeUqi9m\n" .
            "nkX8dlU/02IfRe94h4SBJeJ4kiNYJ+A++1YArSaLGk60Tt4REKjTjGYYx/xvopKR\n" .
            "1OYR8MY=\n" .
            "-----END CERTIFICATE REQUEST-----";

            $webPayCert = "-----BEGIN CERTIFICATE-----\n" .
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
            "-----END CERTIFICATE-----";


            $configuration->setPrivateKey($privateKeyWebPay);
            $configuration->setPublicCert($publicCertWebPay);
            $configuration->setWebpayCert($webPayCert);

            //$transaction = new Webpay($configuration);
            $transaction = (new Webpay($configuration))->getNormalTransaction();
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
