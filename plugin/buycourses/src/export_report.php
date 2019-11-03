<?php
/**
 * Created by PhpStorm.
 * User: aragonc
 * Date: 15/10/19
 * Time: 12:10 AM
 */

//Initialization
$cidReset = true;

require_once '../config.php';

api_protect_admin_script();

$plugin = BuyCoursesPlugin::create();
$form = new FormValidator('export_validate');

$form->addDatePicker('date_start', get_lang('DateStart'), false);
$form->addDatePicker('date_end', get_lang('DateEnd'), false);
$form->addButton('export_sales',get_lang('ExportExcel'));
$salesStatus = [];

if ($form->validate()) {
    $reportValues = $form->getSubmitValues();

    $dateStart = $reportValues['date_start'];
    $dateEnd = $reportValues['date_end'];

    $salesStatus = $plugin->getSaleListReport($dateStart,$dateEnd);
}


if(!empty($salesStatus)){
    $archiveFile = 'export_report_sales_'.api_get_local_time();

    Export::arrayToXls($salesStatus, $archiveFile);
}

$templateName = $plugin->get_lang('ExportReport');

$template = new Template($templateName);
$template->assign('form', $form->returnForm());
$content = $template->fetch('buycourses/view/export_report.tpl');
$template->assign('header', $templateName);
$template->assign('content', $content);
$template->display_one_col_template();