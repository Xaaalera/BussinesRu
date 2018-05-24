<?php
/**
 * Created by PhpStorm.
 * User: xaaalera
 * Date: 20.03.18
 * Time: 8:09
 */


namespace Business;

class Deal extends HelpEntity
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function createOrder()
    {
    
    }
    
    public function createDeal()
    {
        $this->options['partner_id'] = Contact::$partnerid;
        $res = $this->getDeals($this->options);
        $resCheck = 0;
        if (isset($res['result']) && !empty($res['result'])) {
            $this->entity = $res['result'];
            $status = new Status();
            $resCheck = $status->statusChecker($res['result']);
        } elseif (!isset($res['result'])) {
            $resCheck = 1;
        }
        if ($resCheck) {
            $this->options['date'] = date("Y-m-d H:i:s");
            $res = $this->create('deals', $this->options);
        }
        return $res;
        
    }
    
    public function getDeals(array $options = array())
    {
        parent::get('deals', $options);
    }
    
    
}