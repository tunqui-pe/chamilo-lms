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
$salesStatus = $plugin->getSaleListReport();

if(!empty($salesStatus)){
    $archiveFile = 'export_report_sales_'.api_get_local_time();
    $salesListToExport[] = [
        'IDOrder',
        'OrderReference',
        'OrderStatus',
        'OrderDate',
        'PaymentMethod',
        'Price',
        'ProductType',
        'ProductName',
        'UserName',
        'Email'
    ];
    foreach ($salesStatus as $sale){

        $statusSaleOrder = $sale['status'];

        switch ($statusSaleOrder){
            case 0:
                $textStatus = $plugin->get_lang('SaleStatusPending');
                break;
            case 1:
                $textStatus = $plugin->get_lang('SaleStatusCompleted');
                break;
            case -1:
                $textStatus = $plugin->get_lang('SaleStatusCanceled');
                break;
        }

        $salesListToExport[] = [
            'id' => $sale['id'],
            'reference' => $sale['reference'],
            'status' => $textStatus,
            'date' => api_convert_and_format_date($sale['date'], DATE_TIME_FORMAT_LONG_24H),
            'currency' => $sale['iso_code'],
            'price' => $sale['price'],
            'product_type' => $sale['product_type'],
            'product_name' => $sale['product_name'],
            'complete_user_name' => api_get_person_name($sale['firstname'], $sale['lastname']),
            'email' => $sale['email'],
        ];
    }
    Export::arrayToXls($salesListToExport, $archiveFile);
}