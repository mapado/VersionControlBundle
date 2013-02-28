<?php

namespace Mapado\VersionControlBundle\Tests\Units\Doctrine;

use Mapado\VersionControlBundle\Model;
use Mapado\VersionControlBundle\Doctrine;

use \atoum;

class VersionManager extends atoum
{
    const VERSIONNED_CLASS = 'Mapado\VersionControlBundle\Entity\Versionned';
    /**
     * objectManager
     * 
     * @var ObjectManager
     * @access private
     */
    private $objectManager;

    /**
     * repository
     * 
     * @var ObjectRepository
     * @access private
     */
    private $repository;

    /**
     * versionManager
     * 
     * @var VersionManager
     * @access private
     */
    private $versionManager;

    /**
     * versionList
     * 
     * @var array
     * @access private
     */
    private $versionList;

    /**
     * beforeTestMethod
     *
     * @access public
     * @return void
     */
    public function beforeTestMethod()
    {
        // set tests datas
        $this->setTestsDatas();

        // initialize mock
        $class = new \mock\Doctrine\Common\Persistence\Mapping\ClassMetada;
        $this->objectManager = new \mock\Doctrine\Common\Persistence\ObjectManager;
        $this->repository = new \mock\Doctrine\Common\Persistence\ObjectRepository;

        $class->getMockController()->getName = static::VERSIONNED_CLASS;

        // overload methods
        $ommc = $this->objectManager->getMockController();
        $ommc->getRepository = $this->repository;
        $ommc->getClassMetadata = $class;

        $rmc = $this->repository->getMockController();
        $rmc->findBy = function ($params) {
            $sp = array();
            $return = array();
            foreach ($this->versionList as $version) {
                $found = true;

                // compare each parameter
                foreach ($params as $key => $param) {
                    if ($version->{'get' . ucfirst($key)}() != $param) {
                        $found = false;
                        break;
                    }
                }

                if ($found) {
                    $return[] = $version;
                }
            }

            return $return;
        };
        $rmc->findOneBy = function ($params) {
            $list = $this->repository->findBy($params);
            if (!empty($list)) {
                return array_shift($list);
            }
            return null;
        };


        // initialize VersionManager
        $this->versionManager = new Doctrine\VersionManager($this->objectManager, static::VERSIONNED_CLASS);
    }

    /**
     * testVersionNumber
     *
     * @access public
     * @return void
     */
    public function testVersionNumber()
    {
        // test pass version number
        // 1
        $object = new Model\SimpleVersion('mapado.com', 'domain');
        $task = new Model\SimpleTask('parsing');
        $vn = $this->versionManager->getVersionNumber($object, $task);

        $this->object($vn)
            ->isInstanceOf('\Mapado\VersionControlBundle\Model\VersionNumber')
            ->castToString($vn)
            ->isEqualTo('1.2.0');

        // 2
        $object = new Model\SimpleVersion('25', 'final_data');
        $task = new Model\SimpleTask('validation.date');
        $vn = $this->versionManager->getVersionNumber($object, $task);

        $this->object($vn)
            ->isInstanceOf('\Mapado\VersionControlBundle\Model\VersionNumber')
            ->castToString($vn)
            ->isEqualTo('1.2.0');

        // test fail version number
        $object = new Model\SimpleVersion('google.com', 'domain');
        $task = new Model\SimpleTask('parsing');
        $vn = $this->versionManager->getVersionNumber($object, $task);
        $this->variable($vn)->isNull();

        $object = new Model\SimpleVersion('mapado.com', 'domain');
        $task = new Model\SimpleTask('validation.date');
        $vn = $this->versionManager->getVersionNumber($object, $task);
        $this->variable($vn)->isNull();
    }

    /**
     * testTaskList
     *
     * @access public
     * @return void
     */
    public function testTaskList()
    {
        // 1
        $versionnable = new Model\SimpleVersion('25', 'final_data');
        $taskList = $this->versionManager->getTaskList($versionnable);
        $this->sizeOf($taskList)->isEqualTo(2);

        // 2
        $versionnable = new Model\SimpleVersion('mapado.com', 'domain');
        $taskList = $this->versionManager->getTaskList($versionnable);
        $this->sizeOf($taskList)->isEqualTo(1);

        // 3
        $versionnable = new Model\SimpleVersion('plop.com', 'domain');
        $taskList = $this->versionManager->getTaskList($versionnable);
        $this->sizeOf($taskList)->isZero();

        // errors
        $this->when($this->versionManager->getTaskList(new \StdClass))
            ->error()
                ->exists();

        $this->when($this->versionManager->getTaskList(null))
            ->error()
                ->exists();
    }

    /**
     * testObjectListNoVersion
     *
     * @access public
     * @return void
     */
    public function testObjectListNoVersion()
    {
        // 1
        $task = new Model\SimpleTask('validation.date');
        $objectList = $this->versionManager->getObjectList($task);
        $this->sizeOf($objectList)->isEqualTo(2);
        
        // 2
        $task = new Model\SimpleTask('parsing');
        $objectList = $this->versionManager->getObjectList($task);
        $this->sizeOf($objectList)->isEqualTo(1);
        
        // 3
        $task = new Model\SimpleTask('plop');
        $objectList = $this->versionManager->getObjectList($task);
        $this->sizeOf($objectList)->isZero();
    }
 
    /**
     * testObjectList
     *
     * @access public
     * @return void
     */
    public function testObjectList()
    {
        $task = new Model\SimpleTask('validation.date');

        $vnc = new Model\VersionNumberComparator('v1.1.3');
        $objectList = $this->versionManager->getObjectList($task, $vnc);
        $this->sizeOf($objectList)->isEqualTo(1);

        $vnc = new Model\VersionNumberComparator('v1.1.32', '=');
        $objectList = $this->versionManager->getObjectList($task, $vnc);
        $this->sizeOf($objectList)->isEqualTo(0);

        $vnc = new Model\VersionNumberComparator('v1.1.3', '>');
        $objectList = $this->versionManager->getObjectList($task, $vnc);
        $this->sizeOf($objectList)->isEqualTo(1);

        $vnc = new Model\VersionNumberComparator('v1.1.3', '>=');
        $objectList = $this->versionManager->getObjectList($task, $vnc);
        $this->sizeOf($objectList)->isEqualTo(2);

        $vnc = new Model\VersionNumberComparator('v1.1.3', '!=');
        $objectList = $this->versionManager->getObjectList($task, $vnc);
        $this->sizeOf($objectList)->isEqualTo(1);

        $vnc = new Model\VersionNumberComparator('v1.1.3', '<=');
        $objectList = $this->versionManager->getObjectList($task, $vnc);
        $this->sizeOf($objectList)->isEqualTo(1);

        $vnc = new Model\VersionNumberComparator('v1.1.3', '<');
        $objectList = $this->versionManager->getObjectList($task, $vnc);
        $this->sizeOf($objectList)->isEqualTo(0);

        $vnc = new \mock\Mapado\VersionControlBundle\Model\VersionNumberComparator('v1');
        $vnc->getMockController()->getOperator = 'plop';
        $this->exception(
            function () use ($task, $vnc) {
                $this->versionManager->getObjectList($task, $vnc);
            }
        )->isInstanceOf('\UnexpectedValueException');
    }

    /**
     * testValid
     *
     * @access public
     * @return void
     */
    public function testValid()
    {
        $trueTaskList = array(
            array(
                'task' => new Model\SimpleTask('validation.date'),
                'versionnable' => new Model\SimpleVersion('25', 'final_data'),
                'vnc' => new Model\VersionNumberComparator('v1.2.0')
            ),
            array(
                'task' => new Model\SimpleTask('validation.place'),
                'versionnable' => new Model\SimpleVersion('a8eiu82s5', 'raw_data'),
                'vnc' => new Model\VersionNumberComparator('v2.1', '>=')
            ),
            array(
                'task' => new Model\SimpleTask('validation.date'),
                'versionnable' => new Model\SimpleVersion('25', 'final_data'),
                'vnc' => new Model\VersionNumberComparator('v2.0', '!=')
            ),
            array(
                'task' => new Model\SimpleTask('parsing'),
                'versionnable' => new Model\SimpleVersion('mapado.com', 'domain'),
                'vnc' => new Model\VersionNumberComparator('v2.0', '<=')
            ),
            array(
                'task' => new Model\SimpleTask('not.a.task'),
                'versionnable' => new Model\SimpleVersion('000', 'not.an.object'),
                'vnc' => new Model\VersionNumberComparator('v1.1.3', '!=')
            ),
        );

        $falseTaskList = array(
            array(
                'task' => new Model\SimpleTask('validation.date'),
                'versionnable' => new Model\SimpleVersion('25', 'final_data'),
                'vnc' => new Model\VersionNumberComparator('v1.2.0', '>')
            ),
            array(
                'task' => new Model\SimpleTask('validation.place'),
                'versionnable' => new Model\SimpleVersion('a8eiu82s5', 'raw_data'),
                'vnc' => new Model\VersionNumberComparator('v2.1', '<')
            ),
            array(
                'task' => new Model\SimpleTask('validation.date'),
                'versionnable' => new Model\SimpleVersion('25', 'final_data'),
                'vnc' => new Model\VersionNumberComparator('v2.0', '=')
            ),
            array(
                'task' => new Model\SimpleTask('not.a.task'),
                'versionnable' => new Model\SimpleVersion('000', 'not.an.object'),
                'vnc' => new Model\VersionNumberComparator('v1.1.3', '=')
            ),
        );

        foreach ($trueTaskList as $t) {
            $valid = $this->versionManager->isValid($t['versionnable'], $t['task'], $t['vnc']);
            $this->boolean($valid)->isTrue();
        }

        foreach ($falseTaskList as $t) {
            $valid = $this->versionManager->isValid($t['versionnable'], $t['task'], $t['vnc']);
            $this->boolean($valid)->isFalse();
        }

        $vnc = new \mock\Mapado\VersionControlBundle\Model\VersionNumberComparator('v1');
        $vnc->getMockController()->getOperator = 'plop';
        $this->exception(
            function () use ($vnc) {
                $task = new Model\SimpleTask('validation.date');
                $versionnable = new Model\SimpleVersion('25', 'final_data');
                $this->versionManager->isValid($versionnable, $task, $vnc);
            }
        )->isInstanceOf('\UnexpectedValueException');
    }

    /**
     * getTestDatas
     *
     * @access public
     * @return array
     */
    private function setTestsDatas()
    {
        $vList = array(
            0 => new Model\SimpleVersion('mapado.com', 'domain'),
            1 => new Model\SimpleVersion('a8eiu82s5', 'raw_data'),
            2 => new Model\SimpleVersion('25', 'final_data'),
        );

        $tList = array(
            0 => new Model\SimpleTask('parsing'),
            1 => new Model\SimpleTask('validation.date'),
            2 => new Model\SimpleTask('validation.place'),
        );

        $vnList = array(
            0 => new Model\VersionNumber('v1.2.0'),
            1 => new Model\VersionNumber('v1.1.3'),
            2 => new Model\VersionNumber('v2.1'),
            3 => new Model\VersionNumber('v1.2'),
            4 => new Model\VersionNumber('v2.1.0'),
        );

        $versionList = array();

        $version = new Model\Versionned;
        $version->setVersionnable($vList[0])
                ->setTask($tList[0])
                ->setVersionNumber($vnList[0]);
        $versionList[] = $version;

        $version = new Model\Versionned;
        $version->setVersionnable($vList[1])
                ->setTask($tList[1])
                ->setVersionNumber($vnList[1]);
        $versionList[] = $version;

        $version = new Model\Versionned;
        $version->setVersionnable($vList[1])
                ->setTask($tList[2])
                ->setVersionNumber($vnList[2]);
        $versionList[] = $version;

        $version = new Model\Versionned;
        $version->setVersionnable($vList[2])
                ->setTask($tList[1])
                ->setVersionNumber($vnList[3]);
        $versionList[] = $version;

        $version = new Model\Versionned;
        $version->setVersionnable($vList[2])
                ->setTask($tList[2])
                ->setVersionNumber($vnList[4]);
        $versionList[] = $version;

        $this->versionList = $versionList;
    }
}
