<?php

namespace Mapado\VersionControlBundle\Tests\Units\Model;

use Mapado\VersionControlBundle\Model;

use \atoum;

class SimpleTask extends atoum
{
    /**
     * getObject
     *
     * @access private
     * @return SimpleTask
     */
    private function getObject()
    {
        return new Model\SimpleTask;
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
            'name',
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
     * testName
     *
     * @access public
     * @return void
     */
    public function testName()
    {
        $o = $this->getObject();
        $o->setName('mapado.task_name');

        $this->string($o->getName())
            ->isEqualTo('mapado.task_name');
    }

    /**
     * testConstruct
     *
     * @access public
     * @return void
     */
    public function testConstruct()
    {
        $o = new Model\SimpleTask('mapado.task_name');
        
        $this->string($o->getName())
            ->isEqualTo('mapado.task_name');
    }

    /**
     * testInterface
     *
     * @access public
     * @return void
     */
    public function testInterface()
    {
        $o = $this->getObject();
        $interface = '\Mapado\VersionControlBundle\Model\TaskInterface';

        $this->class(get_class($o))
            ->hasInterface($interface);

        $o->setName('mapado.task_name');

        $this->string($o->getTaskName())
            ->isEqualTo('mapado.task_name');
    }

    /**
     * testJsonSerialize
     *
     * @access public
     * @return void
     */
    public function testJsonSerialize()
    {
        $vn = new Model\SimpleTask('mapado.task_name');
        $this->object($vn)
            ->isInstanceOf('\JsonSerializable')
            ->variable(json_encode($vn))
            ->isEqualTo('{"task_name":"mapado.task_name"}');
    }
}
