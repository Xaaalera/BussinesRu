<?php
/**
 * Created by PhpStorm.
 * User: xaaalera
 * Date: 13.04.18
 * Time: 18:03
 */

require_once ('Businessru/autoload.php');
use Business\Business;
use Business\Contact;
use Business\Order;
use Business\Status;
Business::$productId= 1234;
Business::$apiKey= 'api-key';
Business::$urlProject ='https://exemple.class365.ru';
Status::setStatusCheck(array('Отменен','Отгружен с переплатой','Закрыт с переплатой'));
$data = json_decode(trim(file_get_contents('php://input')), true);

if(!isset($data['caller'])) return ;
$domainName = $data['domain'];
$domainName = parse_url($domainName);
$domainName = $domainName['host'];
$comment = isset($data['link']) ? 'Запись разговора: '. $data['link'] : 'Клиент начал звонок' ;
$order = new Order();
$order->setAuthorEmployeeId(282811);
$order->setOrganizationId(75535);
switch ($domainName){
    case 'crumb.ru':
        $order->setResponsibleEmployeeId(82669);
        $order->setExecutorEmployeeId(82669);
        break;
    case 'gumbit.ru':
        $order->setResponsibleEmployeeId(101375);
        $order->setExecutorEmployeeId(101375);
        break;
    case 'cp-ss.ru':
        $order->setResponsibleEmployeeId(87410);
        $order->setExecutorEmployeeId(87410);
        break;
    default:
        $order->setResponsibleEmployeeId(75604);
        $order->setExecutorEmployeeId(75604);
}


$contact = new Contact();
$contact->setPhone($data['caller']);
$order->setName('Звонок от '.$data['caller']);
$order->setDescription('');
#################################################################
$order->setCustomFields(339130,$data['visit_id']); //roistat +
$order->setCustomFields(110240,$contact->getPhone()); //Контакт phone
$order->setCustomFields(339208,$contact->getEmail()); //Контакт Email
$order->setCustomFields(339139,'Звонок'); //FormName
$order->setCustomFields(339131,$domainName); //Domain
$order->setCustomFields(339132,$data['landing_page']); //LeadSource
$order->setComment($comment); //comment
#################################################################
//mail('exemple @mail.ru','OPTIONS',print_r($order->getOptions(),1));
$contact->createPartner();
$order->createOrder());
