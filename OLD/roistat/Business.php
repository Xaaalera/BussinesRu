<?php
require_once('Business_ru_api_lib.php');

/**
 * Created by PhpStorm.
 * User: Xaalera
 * Date: 2/20/2018
 * Time: 11:01 AM
 */

/**
 *
 * [id] => 1[name] => Телефон,[id] => 2[name] => Skype,[id] => 3[name] => Адрес,[id] => 4[name] => Email,
 * [id] => 5[name] => Прочее,[id] => 6[name] => Сайт,[id] => 7[name] => ICQ,id] => 8[name] => Вконтакте,
 * [id] => 9 [name] => Facebook,[id] => 10[name] => Twitter,[id] => 11[name] => Одноклассники
 *
 **/
class Business
{
    private $api, $partnerID;

    public function __construct($productId, $apiKey, $urlProject = 'https://exemple.class365.ru')
    {

        $this->api = new Business_ru_api_lib($productId, '', $urlProject);
        $this->api->setSecret($apiKey);
        $this->api->setToken($this->api->repair()['token']);
    }

    public function newPartner($name, array $contactInfo, array $options = array())
    {
        if (empty($options)) {
            $options = array('name' => $name);
        }
        $partnter_id = $this->searchDubliacate($contactInfo);
        if (empty($partnter_id)) $partnter_id = $this->create('partners', $options)['result']['id'];
        $this->partnerID = $partnter_id;
        $this->newPartnerContactInfo($contactInfo);
    }

    private function searchDubliacate(array  $contactInfo)
    {
        $countcontactInfo = count($contactInfo);
        if ($countcontactInfo > 1) {
            $result = [];
            foreach ($contactInfo as $key => $value) {
                $resultate = $this->getContactInfo(array('contact_info' => $contactInfo));
                $result[]  = $resultate['result'][0]['partner_id'] ? $resultate['result'][0]['partner_id'] : '';
            }
            $result = array_diff($result, array(''));
            $res    = !empty($result) ? array_unique($result[0]) : '';
        }
        else {
            $res = $this->getContactInfo(array('contact_info' => array_values($contactInfo)[0]));
            $res = $res['result'][0]['partner_id'] ? $res['result'][0]['partner_id'] : '';
        }

        return $res;
    }

    public function newPartnerContactInfo($array, $partner_id = '')
    {
        if (!empty($partner_id)) {
            $this->partnerID = $partner_id;
        }
        $strOptions               = array();
        $res                      = array();
        $strOptions['partner_id'] = $this->partnerID;
        foreach ($array as $idInfo => $vaueInfo) {
            $strOptions['contact_info_type_id'] = $idInfo;
            $strOptions['contact_info']         = $vaueInfo;
            $res[]                              = $this->testAndCreate('partnercontactinfo', $strOptions);
        }

        return $res;

    }

    public function newDeal(array $options)
    {

        $options['with_additional_fields'] = 1;
        $options['partner_id']             = $options['partner_id'] ? $options['partner_id'] : $this->partnerID;
        $res                               = $this->testAndCreate('deals', $options);

        return $res;

    }

    public function setPartnerId($partnerID)
    {
        $this->partnerID = $partnerID;
    }

    public function getPartnerId()
    {
        return $this->partnerID;
    }

    public function getDeals(array $options = array())
    {
        return $this->get('deals', $options);
    }

    public function getContactInfo(array $options = array())
    {
        return $this->get('partnercontactinfo', $options);
    }

    public function getPartners(array $options = array())
    {
        return $this->get('partners', $options);
    }

    private function testAndCreate($model, array $options)
    {
        $res = $this->get($model, $options);
        if (!($res['result'])) {
            $res = $this->create($model, $options);
        }

        return $res;
    }

    private function create($model, array $options)
    {
        try {
            $res = $this->api->request('post', $model, $options);
            if ($res['result']['status'] == 'error') throw  new Exception($res['result']['error_code']);
        }
        catch (Exception $e) {
            echo 'Возникла Ошибка ', $e;
        }

        return $res;
    }

    private function get($model, $options)
    {
        return $this->api->request('get', $model, $options);
    }

}