<?php
/**
 * Created by PhpStorm.
 * User: xaaalera
 * Date: 20.03.18
 * Time: 8:11
 */


namespace Business;

class Order extends HelpEntity
{
    public function __construct()
    {
        parent::__construct();
    }
    
    
    public function createOrder()
    {
        $this->options['partner_id'] = Contact::$partnerid;
        $new_options =  $this->options ;
        unset($new_options['comment']);
        $new_options = array_filter($new_options, function($element) {
            return !empty($element);
        });
        $res = Order::getOrders($new_options);
        $resCheck = 0;
        if (isset($res['result']) && !empty($res['result'])) {
            $this->entity = $res['result'];
            $status = new Status();
            $resCheck = $status->statusChecker($res['result']);
            if (!$resCheck){
                $this->updateOrder($this->options,$res);
            }
        } elseif (!isset($res['result'])|| empty($res['result'])) {
            $resCheck = 1;
        }
        if ($resCheck) {
            $this->options['date'] = date("Y-m-d H:i:s");
            $res = $this->create('customerorders', $this->options);
        }
        return $res;
    }
    
    static function getOrders(array $options = array())
    {
        $o = new Order();
        return $o->get('customerorders', $options);
    }
    
    public  function getBasket($options){
        return $this->get('customerordergoods',$options);
    }
    
    private  function  updateOrder($options,$orders){

        return $this->put('customerorders',$options,$orders);
    }
    //TODO Блокиркует из-за колличества запросов
    /*
     *  $basket = new Order() ;
            $basket = $basket->getBasket(['customer_order_id'=>$deals_row['id']]);
            $contact = Contact::partners(['id'=>94811]);
            print_r($basket);
            print_r($deals_row);
            print_r($contact);
     */
    
}