<?php
/**
 * Created by PhpStorm.
 * User: xaaalera
 * Date: 20.03.18
 * Time: 8:02
 */

namespace Business;

class Business
{
    public static $productId, $apiKey, $urlProject;
    protected $api, $statusNameEnity, $entity;
    private static $privateExemplar = 0;
    protected static $BusinessWorker;
    
    protected function __construct()
    {
        
        $this->api = new Business_ru_api_lib(self::$productId, '', self::$urlProject);
        $this->api->setSecret(self::$apiKey);
        $this->api->setToken($this->api->repair()['token']);
    }
    
    
    protected function create($model, array $options)
    {
        return $this->api->request('post', $model, $options);
    }
    
    protected function get($model, $options)
    {
        return $this->api->request('get', $model, $options);
    }
    
    protected function put($model, $options,$orders)
    {
        $id=(int)$orders['result'][0]['id'];
        $option['id'] =$id;
        $comment = isset($options['comment']) && !(empty($options['comment'])) ? $options['comment'] : 'Повторная заявка';
        $option['comment'] = <<<COMMENT
{$orders['result'][0]['comment']}
{$comment}
COMMENT;
        return $this->api->request('put', $model, $option);
    }
    
    protected function testAndCreate($model, array $options)
    {
        $res = $this->get($model, $options);
        if (!($res['result'])) {
            $res = $this->create($model, $options);
        }
        
        return $res;
    }
    
    public static function init()
    {
        if (self::$privateExemplar) {
            return self::$BusinessWorker->api;
        } else {
            self::$BusinessWorker = new Business();
            self::$privateExemplar = 1;
            return self::$BusinessWorker->api;
        }
    }
    
    
}