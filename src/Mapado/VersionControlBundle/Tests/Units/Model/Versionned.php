<?php

namespace Mapado\VersionControlBundle\Tests\Units\Model;

use Mapado\VersionControlBundle\Model;

use \atoum;

class Versionned extends atoum
{

    /**
     * getObject
     *
     * @access private
     * @return Versionned
     */
    private function getObject()
    {
        return new Model\Versionned;
    }
    
    /**
     * getParamList
     *
     * @access private
     * @return array
     */
    private function getParamList()
    {
        return array(
            'versionNumber',
            'versionnable',
            'task',
        );
    }
    
    /**
     * testSetters
     *
     * @access public
     * @return void
     */
    public function testSetters()
    {
        $c = $this->class(get_class($this->getObject()));
        $pList = $this->getParamList();

        foreach ($pList as $p) {
            $c->hasMethod('get' . ucfirst($p))
                ->hasMethod('set' . ucfirst($p));
        }
    }

    /**
     * testVersionNumber
     *
     * @access public
     * @return void
     */
    public function testVersionNumber()
    {
        $o = $this->getObject();
        
        $o->setVersionNumber(new Model\VersionNumber);

        $this->object($o->getVersionNumber())
            ->isInstanceOf('\Mapado\VersionControlBundle\Model\VersionNumber');

        // type is not a version number
        /*
        $this->when(
            function () use ($o) {
                $o->setVersionNumber('1.2.3');
            }
        )->error()
            ->exists();
        */
    }

    /**
     * testVersionnable
     *
     * @access public
     * @return void
     */
    public function testVersionnable()
    {
        $o = $this->getObject();

        $versionnable = new \mock\Mapado\VersionControlBundle\Model\Versionnable;
        
        $o->setVersionnable($versionnable);

        $this->object($o->getVersionnable())
            ->isInstanceOf('\Mapado\VersionControlBundle\Model\Versionnable');

        /*
        $this->when(
            function () use ($o) {
                $o->setVersionnable(new \StdClass);
            }
        )->error()
            ->exists();
        */
    }

    /**
     * testTask
     *
     * @access public
     * @return void
     */
    public function testTask()
    {
        $o = $this->getObject();

        $task = new \mock\Mapado\VersionControlBundle\Model\TaskInterface;
        
        $o->setTask($task);

        $this->object($o->getTask())
            ->isInstanceOf('\Mapado\VersionControlBundle\Model\TaskInterface');

        /*
        $this->when(
            function () use ($o) {
                $o->setTask(new \StdClass);
            }
        )->error()
            ->exists();
        */
    }

    /**
     * testJsonSerialize
     *
     * @access public
     * @return void
     */
    public function testJsonSerialize()
    {
        $t = new Model\SimpleTask('mapado.task_name');
        $v = new Model\SimpleVersion(25, 'version_type');
        $vn = new Model\VersionNumber('0.8.2');

        $o = new Model\Versionned;
        $o->setTask($t)
            ->setVersionnable($v)
            ->setVersionNumber($vn);

        $output = '{'.
            '"version_number":{"complete":"0.8.2","major":"0","minor":"8","maintenance":"2"},' .
            '"task_name":"mapado.task_name",' .
            '"versionnable":{"id":"25","type":"version_type"}}';

        $this->object($o)
            ->isInstanceOf('\JsonSerializable')
            ->variable(json_encode($o))
            ->isEqualTo($output);
    }
}
