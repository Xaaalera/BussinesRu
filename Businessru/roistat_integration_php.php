<?php
error_reporting(E_ALL);
//Если вы хотите закрыть доступ к этой странице по прямой ссылке, то можно указать логин и пароль
$user     = 'roistat';
$password = 'Password';
$token = isset($_GET['token']) ? $_GET['token'] : null;
if ($token !== md5($user.$password)) {
    exit('Invalid token');
}

#================================================================================================================

require_once ('Businessru/autoload.php');
use Business\Business;
use Business\Contact;
use Business\Order;
use Business\Status;
Business::$productId= 1234;
Business::$apiKey= 'api-key';
Business::$urlProject ='https://exemple.class365.ru';

#================================================================================================================
$editDate = isset($_GET["date"]) ? (int)$_GET["date"] : time() - 31 * 24 * 60 * 60;
$offset = isset($_GET["offset"]) ? (int)$_GET["offset"] : 0;
$limit = 500;
$deals = Order::getOrders(["with_additional_fields" => '1', "updated" => ["from" => gmdate("Y-m-d\TH:i:s\Z", $editDate)]]);
$dealstatus = Status::orders();
$partners = Contact::partners() ;
$totalCount = count($deals["result"]);
#================================================================================================================

switch ($_GET["action"]) {
    default:
        $response = array(
            'orders'     => array(),
            'statuses'   => array(),
            'pagination' => array(
                'limit'       => $limit,
                'total_count' => $totalCount,
            ),
        );
        foreach ($deals["result"] as $key => $deals_row) {
//            echo "<pre>";
//            print_r($deals_row);
//            die();
            $response['orders'][] = array(
                'id'          => $deals_row['id'],
                'name'        => $deals_row['number'],
                'date_create' => strtotime($deals_row['date']),
                "date_update" => date("d.m.Y H:i:s", strtotime($deals_row['updated'])),
                'status'      => strval($deals_row['status_id']),
                'price'       => $deals_row['sum'], //price - сумма сделки
                'cost'        => '0', //cost - себестоимость
                'roistat'     => $deals_row['339130'],
                'client_id'   => $deals_row['partner_id'],
                'fields'      => array(
                    "Комментарий" => $deals_row[117558],
                    "manager"     => $deals_row['responsible_employee_id'],
                )
            );
        }
        foreach ($dealstatus as $key => $dealstatus_row) {

            $response['statuses'][] = array(
                'id'   => (int)$key,
                'name' => $dealstatus_row,
            );

        }
        break;
    case 'export_clients':
        $response = array(
            'clients'    => array(),
            'pagination' => array(
                'limit'       => $limit,
                'total_count' => $totalCount,
            ),
        );

        foreach ($partners["result"] as $key => $partners_row) {
      //      $partnercontactinfo = $api->request("get", "partnercontactinfo", [
      //              "partner_id"             => $partners_row['id'],
      //             "with_additional_fields" => '1'
      //         ]);
      //      $phone = $partnercontactinfo["result"][0]['contact_info'];
      //      $email = $partnercontactinfo["result"][1]['contact_info'];
            $phone = '';
            $email = '';
            $response['clients'][] = array(
                'id'         => $partners_row['id'],
                'name'       => $partners_row['name'],
                'phone'      => $phone,
                'email'      => $email,
                'birth_date' => '',
            );
        }
        break;
}
//echo"<pre>";
//print_r($response['statuses']);
//die();
echo json_encode($response);
