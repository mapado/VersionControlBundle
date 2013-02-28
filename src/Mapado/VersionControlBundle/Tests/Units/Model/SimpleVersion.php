<?php

namespace Mapado\VersionControlBundle\Tests\Units\Model;

use Mapado\VersionControlBundle\Model;

use \atoum;

class SimpleVersion extends atoum
{

    /**
     * getObject
     *
     * @access private
     * @return SimpleVersion
     */
    private function getObject()
    {
        return new Model\SimpleVersion;
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
            'versionId',
            'versionType',
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
     * testInterface
     *
     * @access public
     * @return void
     */
    public function testInterface()
    {
        $o = $this->getObject();
        $interface = '\Mapado\VersionControlBundle\Model\Versionnable';

        $this->class(get_class($o))
            ->hasInterface($interface);
    }

    /**
     * testConstruct
     *
     * @access public
     * @return void
     */
    public function testEmptyConstruct()
    {
        $o = new Model\SimpleVersion;
        
        $this->variable($o->getVersionType())->isNull();
        $this->variable($o->getVersionId())->isNull();
    }

    /**
     * testConstruct
     *
     * @access public
     * @return void
     */
    public function testNotEmptyConstruct()
    {
        $id = '1eau85';
        $type = 'mapado.version_type';

        $o = new Model\SimpleVersion($id, $type);
        
        $this->variable($o->getVersionType())
            ->isEqualTo($type);
        
        $this->variable($o->getVersionId())
            ->isEqualTo($id);

        $this->exception(
            function () use ($id, $type) {
                $o = new Model\SimpleVersion($id);
            }
        )->isInstanceOf('\InvalidArgumentException');
    }

    /**
     * testVersionType
     *
     * @access public
     * @return void
     */
    public function testVersionType()
    {
        $o = $this->getObject();
        $o->setVersionType('mapado.version_type');

        $this->string($o->getVersionType())
            ->isEqualTo('mapado.version_type');
    }
    
    /**
     * testVersionId
     *
     * @access public
     * @return void
     */
    public function testVersionId()
    {
        $o = $this->getObject();

        // int
        $o->setVersionId(12);
        $this->integer($o->getVersionId())
            ->isEqualTo(12);

        // float
        $o->setVersionId(12.1);
        $this->float($o->getVersionId())
            ->isEqualTo(12.1);

        // string
        $o->setVersionId('1he0523de9');
        $this->string($o->getVersionId())
            ->isEqualTo('1he0523de9');

        // bool
        $this->exception(
            function () use ($o) {
                $o->setVersionId(true);
            }
        )->isInstanceOf('\InvalidArgumentException');

        // array
        $this->exception(
            function () use ($o) {
                $o->setVersionId(array());
            }
        )->isInstanceOf('\InvalidArgumentException');

        // object
        $this->exception(
            function () use ($o) {
                $o->setVersionId(new \StdClass);
            }
        )->isInstanceOf('\InvalidArgumentException');

        // null
        $this->exception(
            function () use ($o) {
                $o->setVersionId(null);
            }
        )->isInstanceOf('\InvalidArgumentException');
    }

    /**
     * testJsonSerialize
     *
     * @access public
     * @return void
     */
    public function testJsonSerialize()
    {
        $id = 85;
        $type = 'mapado.version_type';
        $o = new Model\SimpleVersion($id, $type);
        
        $this->object($o)
            ->isInstanceOf('\JsonSerializable')
            ->variable(json_encode($o))
            ->isEqualTo(
                '{"id":"85","type":"mapado.version_type"}'
            );
    }
}
