<?php
/* For license terms, see /license.txt */

/**
 * Configuration script for the Buy Courses plugin.
 *
 * @package chamilo.plugin.buycourses
 */
$cidReset = true;

require_once '../config.php';

api_protect_admin_script();

$id = isset($_REQUEST['id']) ? (int) $_REQUEST['id'] : 0;
$type = isset($_REQUEST['type']) ? (int) $_REQUEST['type'] : 0;

if (empty($id) || empty($type)) {
    api_not_allowed();
}

$plugin = BuyCoursesPlugin::create();

$commissionsEnable = $plugin->get('commissions_enable');

if ($commissionsEnable == 'true') {
    $htmlHeadXtra[] = '<script type="text/javascript" src="'.api_get_path(WEB_PLUGIN_PATH)
        .'buycourses/resources/js/commissions.js"></script>';
    $commissions = '';
}

$includeSession = $plugin->get('include_sessions') === 'true';
$editingCourse = $type === BuyCoursesPlugin::PRODUCT_TYPE_COURSE;
$editingSession = $type === BuyCoursesPlugin::PRODUCT_TYPE_SESSION;

$entityManager = Database::getManager();
$userRepo = UserManager::getRepository();
$currency = $plugin->getSelectedCurrency();

if (empty($currency)) {
    Display::addFlash(
        Display::return_message($plugin->get_lang('CurrencyIsNotConfigured'), 'error')
    );
}

$currencyIso = null;

if ($editingCourse) {
    $course = $entityManager->find('ChamiloCoreBundle:Course', $id);

    if (!$course) {
        api_not_allowed(true);
    }

    /*if (!$plugin->isValidCourse($course)) {
        api_not_allowed(true);
    }*/

    $courseItem = $plugin->getCourseForConfiguration($course, $currency);
    $defaultBeneficiaries = [];
    $teachers = $course->getTeachers();
    $teachersOptions = [];

    foreach ($teachers as $courseTeacher) {
        $teacher = $courseTeacher->getUser();
        $teachersOptions[] = [
            'text' => $teacher->getCompleteName(),
            'value' => $teacher->getId(),
        ];
        $defaultBeneficiaries[] = $teacher->getId();
    }

    $currentBeneficiaries = $plugin->getItemBeneficiaries($courseItem['item_id']);
    if (!empty($currentBeneficiaries)) {
        $defaultBeneficiaries = array_column($currentBeneficiaries, 'user_id');
        if ($commissionsEnable === 'true') {
            $defaultCommissions = array_column($currentBeneficiaries, 'commissions');
            foreach ($defaultCommissions as $defaultCommission) {
                $commissions .= $defaultCommission.',';
            }
            $commissions = substr($commissions, 0, -1);
        }
    }

    $currencyIso = $courseItem['currency'];
    $formDefaults = [
        'product_type' => get_lang('Course'),
        'id' => $courseItem['course_id'],
        'type' => BuyCoursesPlugin::PRODUCT_TYPE_COURSE,
        'name' => $courseItem['course_title'],
        'visible' => $courseItem['visible'],
        'price' => $courseItem['price'],
        'price_usd' => $courseItem['price_usd'],
        'tax_perc' => $courseItem['tax_perc'],
        'beneficiaries' => $defaultBeneficiaries,
        $commissionsEnable == 'true' ? 'commissions' : '' => $commissionsEnable == 'true' ? $commissions : '',
    ];
} elseif ($editingSession) {
    if (!$includeSession) {
        api_not_allowed(true);
    }

    $session = $entityManager->find('ChamiloCoreBundle:Session', $id);
    if (!$session) {
        api_not_allowed(true);
    }

    $sessionItem = $plugin->getSessionForConfiguration($session, $currency);
    $generalCoach = $session->getGeneralCoach();
    $generalCoachOption = [
        'text' => $generalCoach->getCompleteName(),
        'value' => $generalCoach->getId(),
    ];
    $defaultBeneficiaries = [
        $generalCoach->getId(),
    ];
    $courseCoachesOptions = [];
    $sessionCourses = $session->getCourses();

    foreach ($sessionCourses as $sessionCourse) {
        $courseCoaches = $userRepo->getCoachesForSessionCourse($session, $sessionCourse->getCourse());

        foreach ($courseCoaches as $courseCoach) {
            if ($generalCoach->getId() === $courseCoach->getId()) {
                continue;
            }

            $courseCoachesOptions[] = [
                'text' => $courseCoach->getCompleteName(),
                'value' => $courseCoach->getId(),
            ];
            $defaultBeneficiaries[] = $courseCoach->getId();
        }
    }

    $currentBeneficiaries = $plugin->getItemBeneficiaries($sessionItem['item_id']);

    if (!empty($currentBeneficiaries)) {
        $defaultBeneficiaries = array_column($currentBeneficiaries, 'user_id');

        if ($commissionsEnable == 'true') {
            $defaultCommissions = array_column($currentBeneficiaries, 'commissions');

            foreach ($defaultCommissions as $defaultCommission) {
                $commissions .= $defaultCommission.',';
            }

            $commissions = substr($commissions, 0, -1);
        }
    }

    $currencyIso = $sessionItem['currency'];
    $formDefaults = [
        'product_type' => get_lang('Session'),
        'id' => $session->getId(),
        'type' => BuyCoursesPlugin::PRODUCT_TYPE_SESSION,
        'name' => $sessionItem['session_name'],
        'visible' => $sessionItem['visible'],
        'price' => $sessionItem['price'],
        'price_usd' => $sessionItem['price_usd'],
        'tax_perc' => $sessionItem['tax_perc'],
        'is_international' => $sessionItem['is_international'],
        'beneficiaries' => $defaultBeneficiaries,
        $commissionsEnable == 'true' ? 'commissions' : '' => $commissionsEnable == 'true' ? $commissions : '',
    ];
} else {
    api_not_allowed(true);
}

if ($commissionsEnable === 'true') {
    $htmlHeadXtra[] = "
        <script>
            $(function() {
                if ($('[name=\"commissions\"]').val() === '') {
                    $('#panelSliders').html(
                        '<button id=\"setCommissionsButton\" class=\"btn btn-warning\">'
                            + '".get_plugin_lang('SetCommissions', 'BuyCoursesPlugin')."'
                    );
                } else {
                    showSliders(100, 'default', '".$commissions."');
                }
                
                var maxPercentage = 100;
                $('#selectBox').on('change', function() {
                    $('#panelSliders').html('');
                });

                $('#setCommissionsButton').on('click', function() {
                    $('#panelSliders').html('');
                    showSliders(maxPercentage, 'renew');
                });
            });
        </script>
    ";
}

$globalSettingsParams = $plugin->getGlobalParameters();

$form = new FormValidator('beneficiaries');
$form->addText('product_type', $plugin->get_lang('ProductType'), false);
$form->addText('name', get_lang('Name'), false);
$form->addCheckBox(
    'visible',
    $plugin->get_lang('VisibleInCatalog'),
    $plugin->get_lang('ShowOnCourseCatalog')
);
$form->addElement(
    'number',
    'price',
    [$plugin->get_lang('Price'), null, $currencyIso],
    ['step' => 0.01]
);

$isInternationalCheckbox = $form->addCheckBox(
    'is_international',
    $plugin->get_lang('InternationalCourse'),
    $plugin->get_lang('AddPriceUSD')
);

$form->addElement(
    'number',
    'price_usd',
    [$plugin->get_lang('PriceInternational'), null, 'USD'],
    ['step' => 0.01]
);
$form->addElement(
    'number',
    'tax_perc',
    [$plugin->get_lang('TaxPerc'), $plugin->get_lang('TaxPercDescription'), '%'],
    ['step' => 1, 'placeholder' => $globalSettingsParams['global_tax_perc'].'% '.$plugin->get_lang('ByDefault')]
);
$beneficiariesSelect = $form->addSelect(
    'beneficiaries',
    $plugin->get_lang('Beneficiaries'),
    null,
    ['multiple' => 'multiple', 'id' => 'selectBox']
);

if ($editingCourse) {
    $teachersOptions = api_unique_multidim_array($teachersOptions, 'value');
    $beneficiariesSelect->addOptGroup($teachersOptions, get_lang('Teachers'));
} elseif ($editingSession) {
    $courseCoachesOptions = api_unique_multidim_array($courseCoachesOptions, 'value');
    $beneficiariesSelect->addOptGroup([$generalCoachOption], get_lang('SessionGeneralCoach'));
    $beneficiariesSelect->addOptGroup($courseCoachesOptions, get_lang('SessionCourseCoach'));
}

if ($commissionsEnable === 'true') {
    $platformCommission = $plugin->getPlatformCommission();
    $form->addHtml(
        '
        <div class="form-group">
            <label for="sliders" class="col-sm-2 control-label">
                '.get_plugin_lang('Commissions', 'BuyCoursesPlugin').'
            </label>
            <div class="col-sm-8">
                '.Display::return_message(
                    sprintf($plugin->get_lang('TheActualPlatformCommissionIsX'), $platformCommission['commission'].'%'),
                    'info',
                    false
                ).'
                <div id="panelSliders"></div>
            </div>
        </div>'
    );

    $form->addHidden('commissions', '');
}

$form->addHidden('type', null);
$form->addHidden('id', null);
$button = $form->addButtonSave(get_lang('Save'));

if (empty($currency)) {
    $button->setAttribute('disabled');
}

$form->freeze(['product_type', 'name']);

if ($form->validate()) {
    $formValues = $form->exportValues();
    $id = $formValues['id'];
    $type = $formValues['type'];

    $productItem = $plugin->getItemByProduct($id, $type);

    $isInternationalValue = 0;
    if(isset($formValues['is_international'])){
        $isInternationalValue = 1;
    }
    if (isset($formValues['visible'])) {
        $taxPerc = $formValues['tax_perc'] != '' ? (int) $formValues['tax_perc'] : null;
        if (!empty($productItem)) {
            $plugin->updateItem(
                [
                    'price' => floatval($formValues['price']),
                    'tax_perc' => $taxPerc,
                    'price_usd' => floatval($formValues['price_usd']),
                    'is_international' => $formValues['is_international']
                ],
                $id,
                $type
            );
        } else {
            $itemId = $plugin->registerItem([
                'currency_id' => (int) $currency['id'],
                'product_type' => $type,
                'product_id' => $id,
                'price' => floatval($_POST['price']),
                'tax_perc' => $taxPerc,
                'price_usd' => floatval($_POST['price_usd']),
                'is_international' => $isInternationalValue
            ]);
            $productItem['id'] = $itemId;
        }

        $plugin->deleteItemBeneficiaries($productItem['id']);

        if (isset($formValues['beneficiaries'])) {
            if ($commissionsEnable === 'true') {
                $usersId = $formValues['beneficiaries'];
                $commissions = explode(',', $formValues['commissions']);
                $commissions = (count($usersId) != count($commissions))
                    ? array_fill(0, count($usersId), 0)
                    : $commissions;
                $beneficiaries = array_combine($usersId, $commissions);
            } else {
                $usersId = $formValues['beneficiaries'];
                $commissions = array_fill(0, count($usersId), 0);
                $beneficiaries = array_combine($usersId, $commissions);
            }

            $plugin->registerItemBeneficiaries($productItem['id'], $beneficiaries);
        }
    } else {
        $plugin->deleteItem($productItem['id']);
    }

    header('Location: '.api_get_path(WEB_PLUGIN_PATH).'buycourses/src/list.php');
    exit;
}

$form->setDefaults($formDefaults);

// View
$templateName = $plugin->get_lang('AvailableCourse');

$interbreadcrumb[] = [
    'url' => 'paymentsetup.php',
    'name' => get_lang('Configuration'),
];
$interbreadcrumb[] = [
    'url' => 'list.php',
    'name' => $plugin->get_lang('AvailableCourses'),
];

$template = new Template($templateName);
$template->assign('header', $templateName);
$template->assign('content', $form->returnForm());
$template->display_one_col_template();
