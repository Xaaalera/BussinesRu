<?php
/**
 * Created by PhpStorm.
 * User: xaaalera
 * Date: 20.03.18
 * Time: 8:42
 */

namespace Business;

class HelpEntity extends Business
{
    protected $options, $authorEmployeeId, $responsibleEmployeeId, $executorEmployeeId, $organizationId, $name, $description;
    static public $partnerid;
    
    protected function __construct()
    {
        $this->api = self::init();
        $this->setOptionsDefault();
    }
    
    
    private function setOptionsDefault()
    {
        $this->options['with_additional_fields'] = 1;
        $this->options['order_by'] = array('date' => 'DESC');
    }
    
    /**
     * @param int $id
     */
    public function setAuthorEmployeeId($id)
    {
        $this->options['author_employee_id'] = $id;
        
    }
    
    /**
     * @param int $id
     */
    public function setResponsibleEmployeeId($id)
    {
        $this->options['responsible_employee_id'] = $id;
    }
    
    public function setComment($comment)
    {
        $this->options['comment'] = $comment;
    }
    /**
     * @param int $id
     */
    public function setExecutorEmployeeId($id)
    {
        $this->options['executor_employee_id'] = $id;
    }
    
    /**
     * @param  int $id
     */
    public function setOrganizationId($id)
    {
        $this->options['organization_id'] = $id;
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
    
    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->options['name'] = $name;
    }
    
    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->options['description'] = $description;
    }
    
    
}