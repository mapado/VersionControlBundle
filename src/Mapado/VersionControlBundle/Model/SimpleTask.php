<?php

namespace Mapado\VersionControlBundle\Model;

class SimpleTask implements TaskInterface, \JsonSerializable
{
    /**
     * name
     * 
     * @var string
     * @access protected
     */
    protected $name;

    /**
     * __construct
     *
     * @param string $name task name
     * @access public
     * @return void
     */
    public function __construct($name = null)
    {
        if (!empty($name)) {
            $this->setName($name);
        }
    }

    /**
     * Gets the value of name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Sets the value of name
     *
     * @param string $name task name
     *
     * @return SimpleTask
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * getTaskName
     *
     * @access public
     * @return string
     */
    public function getTaskName()
    {
        return $this->getName();
    }

    /**
     * jsonSerialize
     *
     * @access public
     * @return string
     */
    public function jsonSerialize()
    {
        return array('task_name' => $this->getTaskName());
    }
}
