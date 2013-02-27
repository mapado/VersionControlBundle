<?php

namespace Mapado\VersionControlBundle\Model;

use Mapado\VersionControlBundle\Model\Versionnable;
use Mapado\VersionControlBundle\Model\TaskInterface;

/**
 * Versionned
 * 
 * @author Julien DENIAU <julien.deniau@mapado.com>
 */
class Versionned implements \JsonSerializable
{
    /**
     * versionNumber
     * 
     * @var VersionNumber
     * @access protected
     */
    protected $versionNumber;

    /**
     * versionnable
     * 
     * @var Versionnable
     * @access protected
     */
    protected $versionnable;

    /**
     * task
     * 
     * @var TaskInterface
     * @access protected
     */
    protected $task;


    /**
     * Gets the value of versionNumber
     *
     * @return VersionNumber
     */
    public function getVersionNumber()
    {
        return $this->versionNumber;
    }
    
    /**
     * Sets the value of versionNumber
     *
     * @param VersionNumber $versionNumber version number
     *
     * @return Versionned
     */
    public function setVersionNumber(VersionNumber $versionNumber)
    {
        $this->versionNumber = $versionNumber;
        return $this;
    }

    /**
     * Gets the value of versionnable
     *
     * @return Versionnable
     */
    public function getVersionnable()
    {
        return $this->versionnable;
    }
    
    /**
     * Sets the value of versionnable
     *
     * @param Versionnable $versionnable versionnable object
     *
     * @return Versionned
     */
    public function setVersionnable(Versionnable $versionnable)
    {
        $this->versionnable = $versionnable;
        return $this;
    }

    /**
     * Gets the value of task
     *
     * @return TaskInterface
     */
    public function getTask()
    {
        return $this->task;
    }
    
    /**
     * Sets the value of task
     *
     * @param Task $task task
     *
     * @return Versionned
     */
    public function setTask(TaskInterface $task)
    {
        $this->task = $task;
        return $this;
    }

    /**
     * jsonSerialize
     *
     * @access public
     * @return array
     */
    public function jsonSerialize()
    {
        return array(
            'version_number' => $this->getVersionNumber(),
            'task_name' => $this->getTask()->getTaskName(),
            'versionnable' => $this->getVersionnable()
        );
    }
}
