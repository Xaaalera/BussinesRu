<?php
/**
 * Created by PhpStorm.
 * User: xaaalera
 * Date: 20.03.18
 * Time: 8:12
 */

namespace Business;

class Status extends Business
{
    public function __construct()
    {
        $this->api = self::init();
    }
    
    protected static $statusArray;
    
    
    public function statusChecker($entity)
    {
        $status = $entity[0]['status_id'];
        return in_array($status, self::$statusArray);
    }
    
    public static function setStatusCheck($array, $order = 1)
    {
        if ($order) {
            $statusArray = array_flip(Status::orders());
        } else {
            $statusArray = array_flip(Status::deals());
        }
        foreach ($array as $name) {
            $statusId[] = $statusArray[$name];
        }
        self::$statusArray = $statusId;
        
    }
    
    protected function cf($statusNameEnity)
    {
        $this->statusNameEnity = $statusNameEnity;
        $res = file_exists($this->filePathPrepare());
        return $res;
    }
    
    private function filePathPrepare()
    {
        $file = $_SERVER['DOCUMENT_ROOT'];
        $num = strlen($file) - 1; //потому что нам нужно обращятся к элементу массива(строки) ;
        $file = $file{$num} == '/' ? $file{$num} = '' : $file;
        $file .= '/roistat/' . $this->statusNameEnity . '.txt';
        return $file;
    }
    
    private function statusWork($array = '')
    {
        $file = $this->filePathPrepare();
        if (file_exists($file)) {
            return $this->getStatusInFile();
        }
        $file = fopen($file, 'w+');
        $arrayStatus = $this->prepareArray($array);
        fputs($file, serialize($arrayStatus));
        fclose($file);
        return $arrayStatus;
    }
    
    private function getStatusInFile()
    {
        return unserialize(file_get_contents($this->filePathPrepare()));
    }
    
    private function prepareArray($array)
    {
        foreach ($array['result'] as $arraynumber => $needInfo) {
            $id = $needInfo['id'];
            $status[$id] = $needInfo['name'];
        }
        
        return $status;
    }
    
    static public function deals($options = array())
    {
        $status = new Status();
        if ($status->cf('dealStatus')) {
            return $status->statusWork();
        } else {
            $statusArray = $status->get('dealstatus', $options);
            return $status->statusWork($statusArray);
        }
    }
    
    static public function orders($options = array())
    {
        $status = new Status();
        if ($status->cf('orderStatus')) {
            return $status->statusWork();
        } else {
            $statusArray = $status->get('customerorderstatus', $options);
            return $status->statusWork($statusArray);
        }
    }
    
    
}