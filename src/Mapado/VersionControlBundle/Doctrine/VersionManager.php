<?php

namespace Mapado\VersionControlBundle\Doctrine;

use \Doctrine\Common\Persistence\ObjectManager;
use \Mapado\VersionControlBundle\Entity;
use \Mapado\VersionControlBundle\Model\VersionManagerInterface;
use \Mapado\VersionControlBundle\Model\Versionned;
use \Mapado\VersionControlBundle\Model\Versionnable;
use \Mapado\VersionControlBundle\Model\VersionNumber;
use \Mapado\VersionControlBundle\Model\TaskInterface;
use \Mapado\VersionControlBundle\Model\VersionNumberComparator;

/**
 * VersionManager
 * 
 * @uses VersionManagerInterface
 * @author Julien DENIAU <julien.deniau@mapado.com>
 */
class VersionManager implements VersionManagerInterface
{
    /**
     * objectManager
     * 
     * @var ObjectManager
     * @access protected
     */
    protected $objectManager;

    /**
     * repository
     * 
     * @var mixed
     * @access protected
     */
    protected $repository;

    /**
     * class
     * 
     * @var string
     * @access protected
     */
    protected $class;

    /**
     * __construct
     *
     * @param ObjectManager $om
     * @param string $class
     * @access public
     * @return void
     */
    public function __construct(ObjectManager $om, $class)
    {
        $this->objectManager = $om;
        $this->repository = $om->getRepository($class);

        $metadata = $om->getClassMetadata($class);
        $this->class = $metadata->getName();
    }

    /**
     * {@inheritDoc}
     */
    public function getVersionNumber(Versionnable $versionnable, TaskInterface $task)
    {
        $versionned = $this->getObject($versionnable, $task);

        if ($versionned) {
            return $versionned->getVersionNumber();
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function getObject(Versionnable $versionnable, TaskInterface $task)
    {
        $repo = $this->objectManager->getRepository('MapadoVersionControlBundle:Versionned');
        $versionned = $repo->findOneBy(array('versionnable' => $versionnable, 'task' => $task));

        return $versionned;
    }

    /**
     * {@inheritDoc}
     */
    public function getTaskList(Versionnable $versionnable)
    {
        $repo = $this->objectManager->getRepository('MapadoVersionControlBundle:Versionned');

        return $repo->findBy(array('versionnable' => $versionnable));
    }

    /**
     * {@inheritDoc}
     */
    public function getObjectList(TaskInterface $task, VersionNumberComparator $vnc = null)
    {
        $repo = $this->objectManager->getRepository('MapadoVersionControlBundle:Versionned');

        $parameters = array('task' => $task);
        $taskList = $repo->findBy($parameters);

        if (isset($vnc)) {
            $taskList = array_filter(
                $taskList,
                function ($task) use ($vnc) {
                    $vn = $vnc->getVersionNumber();
                    $taskVn = $task->getVersionNumber();
                    $vnCompare = VersionNumber::compare($taskVn, $vn);
                    switch ($vnc->getOperator()) {
                        case '=':
                            return $vnCompare == 0;
                        case '!=':
                            return $vnCompare != 0;
                        case '<=':
                            return $vnCompare <= 0;
                        case '<':
                            return $vnCompare < 0;
                        case '>=':
                            return $vnCompare >= 0;
                        case '>':
                            return $vnCompare > 0;
                        default:
                            throw new \UnexpectedValueException('Operator not defined');
                    }
                }
            );
        }

        return $taskList;
    }

    /**
     * {@inheritDoc}
     */
    public function isValid(Versionnable $versionnable, TaskInterface $task, VersionNumberComparator $vnc)
    {
        $vn = $this->getVersionNumber($versionnable, $task);

        if (!$vn) {
            // if no version number found,
            // let's return true if the operator is "!=`
            return $vnc->getOperator() == '!=';
        }

        $vnCompare = VersionNumber::compare($vn, $vnc->getVersionNumber());
        switch ($vnc->getOperator()) {
            case '=':
                return $vnCompare == 0;
            case '!=':
                return $vnCompare != 0;
            case '<=':
                return $vnCompare <= 0;
            case '<':
                return $vnCompare < 0;
            case '>=':
                return $vnCompare >= 0;
            case '>':
                return $vnCompare > 0;
            default:
                throw new \UnexpectedValueException('Operator not defined');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function update(Versionnable $versionnable, TaskInterface $task, VersionNumber $newVnc)
    {
        $version = $this->getObject($versionnable, $task);
        if (!$version) {
            $version = new Entity\Versionned;
            $version->setVersionnable($versionnable)
                    ->setTask($task)
                    ->setVersionNumber($newVnc);
        } else {
            $this->objectManager->remove($version->getVersionNumber());
            $version->setVersionNumber($newVnc);
        }

        $this->objectManager->persist($newVnc);
        $this->objectManager->persist($version);

        $this->objectManager->flush();
    }
    
    /**
     * {@inheritDoc}
     */
    public function delete(Versionnable $versionnable, TaskInterface $task)
    {
        $version = $this->getObject($versionnable, $task);

        $this->objectManager->remove($version->getVersionNumber());
        $this->objectManager->remove($version);

        $this->objectManager->flush();
    }
}
