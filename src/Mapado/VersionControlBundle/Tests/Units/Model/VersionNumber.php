<?php

namespace Mapado\VersionControlBundle\Tests\Units\Model;

use Mapado\VersionControlBundle\Model;

use \atoum;

class VersionNumber extends atoum
{

    /**
     * getObject
     *
     * @access private
     * @return VersionNumber
     */
    private function getObject()
    {
        return new \Mapado\VersionControlBundle\Model\VersionNumber;
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
            'major',
            'minor',
            'maintenance',
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
     * testMajor
     *
     * @access public
     * @return void
     */
    public function testMajor()
    {
        $o = $this->getObject();

        $o->setMajor('1');

        $this->variable($o->getMajor())
            ->isEqualTo('1');
    }

    /**
     * testMinor
     *
     * @access public
     * @return void
     */
    public function testMinor()
    {
        $o = $this->getObject();

        $o->setMinor('1');

        $this->variable($o->getMinor())
            ->isEqualTo('1');
    }

    /**
     * testMaintenance
     *
     * @access public
     * @return void
     */
    public function testMaintenance()
    {
        $o = $this->getObject();

        $o->setMaintenance('1');

        $this->variable($o->getMaintenance())
            ->isEqualTo('1');
    }

    /**
     * testCreateFromString
     *
     * @access public
     * @return void
     */
    public function testCreateFromString()
    {
        $versionList = array(
            '2.1.3' => '2.1.3',
            'v2.1.3' => '2.1.3',
            ' v2.1.3' => '2.1.3',
            '2.1' => '2.1.0',
            'v2.1' => '2.1.0',
            '2' => '2.0.0',
            'v2' => '2.0.0',
        );

        foreach ($versionList as $input => $output) {
            $o = new Model\VersionNumber($input);
            $str = (string) $o;
            $this->string($str)
                ->isEqualTo($output);
        }


        $this->exception(
            function () {
                new Model\VersionNumber('this is not a version number');
            }
        )->isInstanceOf('\UnexpectedValueException');

        $this->exception(
            function () {
                new Model\VersionNumber('this is not a 2.1.3 number');
            }
        )->isInstanceOf('\UnexpectedValueException');
    }

    /**
     * testCompare
     *
     * @access public
     * @return void
     */
    public function testCompare()
    {
        $a = new Model\VersionNumber('0.1');
        $b = new Model\VersionNumber('1.0');
        $this->integer(Model\VersionNumber::compare($a, $b))
            ->isIdenticalTo(-1);

        $a = new Model\VersionNumber('0.9.2');
        $b = new Model\VersionNumber('1');
        $this->integer(Model\VersionNumber::compare($a, $b))
            ->isIdenticalTo(-1);

        $a = new Model\VersionNumber('1');
        $b = new Model\VersionNumber('1.0.0');
        $this->integer(Model\VersionNumber::compare($a, $b))
            ->isIdenticalTo(0);

        $a = new Model\VersionNumber('3.2');
        $b = new Model\VersionNumber('3.1.9');
        $this->integer(Model\VersionNumber::compare($a, $b))
            ->isIdenticalTo(1);
    }

    /**
     * testJsonSerialize
     *
     * @access public
     * @return void
     */
    public function testJsonSerialize()
    {
        $vn = new Model\VersionNumber('0.9.2');
        $this->object($vn)
            ->isInstanceOf('\JsonSerializable')
            ->variable(json_encode($vn))
            ->isEqualTo('{"complete":"0.9.2","major":"0","minor":"9","maintenance":"2"}');
    }
}
