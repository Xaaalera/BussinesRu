<?php
/**
 * Created by PhpStorm.
 * User: xaaalera
 * Date: 20.03.18
 * Time: 8:11
 */

namespace Business;

class Contact extends Business
{
    protected $partnerID, $options, $name, $email, $phone;
    public static $partnerid;
    
    public function __construct()
    {
        $this->api = self::init();
    }
    
    /**
     * @return array
     */
    public function createPartner()
    {
        $this->options['name'] = $this->name;
        if(!empty($this->phone)) $this->options[1] = $contactInfo[1] = $this->preparePhone($this->phone);
        if(!empty($this->email)) $this->options[4] = $contactInfo[4] = $this->email;
        $partnter_id = $this->searchDubliacate($contactInfo);
        if (empty($partnter_id)) {
            $name  = isset($this->options['name']) && !empty($this->options['name']) ? $this->options['name'] : 'Неизвестный контакт';
            $this->options['name'] = $name ;
            $partnter_id = parent::create('partners', $this->options)['result']['id'];
        }
        $this->setPartnerId((int)$partnter_id);
        $res = $this->newPartnerContactInfo($contactInfo);
        return $res;
    }
    
    /**
     * @param array $array
     * @param string $partner_id
     * @return array
     */
    public function newPartnerContactInfo($array)
    {
        $strOptions = array();
        $res = array();
        $strOptions['partner_id'] = $this->partnerID;
        foreach ($array as $idInfo => $vaueInfo) {
            $strOptions['contact_info_type_id'] = $idInfo;
            $strOptions['contact_info'] = $vaueInfo;
            $res[] = $this->testAndCreate('partnercontactinfo', $strOptions);
        }
        
        return $res;
        
    }
    
    /**
     * @param array $contactInfo
     * @return string|boolean
     */
    private function searchDubliacate(array $contactInfo)
    {
        $res = $this->getContactInfo(array('contact_info' => $contactInfo));
        $res = $res['result'][0]['partner_id'] ? $res['result'][0]['partner_id'] : '';
        return $res;
    }
    
    /**
     * @param array $options
     * @return array
     */
    public function getContactInfo(array $options = array())
    {
        return $this->get('partnercontactinfo', $options);
    }
    
    /**
     * @param array $options
     * @return array
     */
    static function partners(array $options = array())
    {
        $c = new Contact();
        return $c->get('partners', $options);
    }
    
    /**
     * @param int $partnerID
     */
    public function setPartnerId($partnerID)
    {
        
        $this->partnerID = $partnerID;
        Contact::$partnerid = $partnerID;
    }
    
    /**
     * @return int
     */
    public function getPartnerId()
    {
        return $this->partnerID;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    
    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }
    
    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }
    
    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $phone = preg_replace('/[^\d]/', '', $phone);
        $phone = preg_replace('/(^8)/', '7', $phone);
        $this->phone = $phone;
    }
    
    /**
     * @param $name
     * @param $value
     */
    public function setCustomFields($name, $value)
    {
        $this->options[$name] = $value;
    }
    
    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
    
    private function preparePhone($phone)
    {
        return preg_replace('/[^\d]/', '', $phone);
    }
    
}