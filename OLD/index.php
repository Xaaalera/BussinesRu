<?php
require_once('roistat/Business.php');

$business                           = new Business(1234, '647457457547', 'https://exemple.class365.ru');
$roistatVisit                       = isset($_COOKIE['roistat_visit']) ? $_COOKIE['roistat_visit'] : NULL;
$options['author_employee_id']      = 282811;
$options['responsible_employee_id'] = 282811;
$options['organization_id']         = 75535;
$options['executor_employee_id']    = 87410;
$options['name']                    = ("Заявка с формы Обратная связь сайта exemple.com");
$options['description']             = 'Описание ';
$options['326157']                  = "Обратная связь сайта exemple.com";
$options['326155']                  = $roistatVisit;
$options['326543']                  = date("Y-m-d H");
$email                              = 'exemple@mail.ru';
$name                               = 'Иванов Иван Иваныч';
$phone                              = '+71111111111';

$business->newPartner($name, ['1' => $phone, '4' => $email]);
$business->newDeal($options);

