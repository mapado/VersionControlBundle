<?php

namespace Mapado\VersionControlBundle\Tests\Units\Model;

use Mapado\VersionControlBundle\Model;

use \atoum;

class VersionNumberComparator extends atoum
{

    /**
     * getObject
     *
     * @access private
     * @return VersionNumber
     */
    private function getObject()
    {
        return new Model\VersionNumberComparator;
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
            'operator',
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
        $vn = new Model\VersionNumber('2.1.3');

        $o->setVersionNumber($vn);
        $this->object($o->getVersionNumber())
            ->isIdenticalTo($vn);
    }

    /**
     * testOperator
     *
     * @access public
     * @return void
     */
    public function testOperator()
    {
        $o = $this->getObject();

        $acceptedOperatorList = array(
            '>',
            '>=',
            '<',
            '<=',
            '=',
            '!=',
        );

        foreach ($acceptedOperatorList as $operator) {
            $o->setOperator($operator);
            $this->variable($o->getOperator())
                ->isIdenticalTo($operator);
        }

        $this->exception(function() use ($o) {
            $o->setOperator('i\'m not an accepted operator');
        }) ->isInstanceOf('\UnexpectedValueException');
    }

    /**
     * testConstruct
     *
     * @access public
     * @return void
     */
    public function testConstruct()
    {
        $version = 'v2.1';

        // 1
        $o = new Model\VersionNumberComparator($version);
        $this->object($o->getVersionNumber())
            ->isInstanceOf('\Mapado\VersionControlBundle\Model\VersionNumber');
        $this->string((string) $o->getVersionNumber())
            ->isEqualTo('2.1.0');

        $this->variable($o->getOperator())
            ->isEqualTo('=');


        // 2
        $comparator = '<=';
        $o = new Model\VersionNumberComparator($version, $comparator);
        $this->object($o->getVersionNumber())
            ->isInstanceOf('\Mapado\VersionControlBundle\Model\VersionNumber');
        $this->string((string) $o->getVersionNumber())
            ->isEqualTo('2.1.0');

        $this->variable($o->getOperator())
            ->isEqualTo('<=');
    }
}
