<?php

namespace Mapado\VersionControlBundle\Model;

/**
 * VersionManagerInterface
 * 
 * @author Julien DENIAU <julien.deniau@mapado.com>
 */
interface VersionManagerInterface
{
    /**
     * getVersionNumber of task + versionnable
     *
     * @param Versionnable $versionnable
     * @param TaskInterface $task
     * @access public
     * @return VersionNumber
     */
    public function getVersionNumber(Versionnable $versionnable, TaskInterface $task);

    /**
     * getTaskList get Task list + VersionNumber for a versionnable
     *
     * @param Versionnable $versionnable
     * @access public
     * @return array()
     */
    public function getTaskList(Versionnable $versionnable);

    /**
     * getObjectList get an versionnable list for a task and a optional version number comparator
     *
     * @param TaskInterface $task
     * @param VersionNumberComparator $vnc
     * @access public
     * @return array()
     */
    public function getObjectList(TaskInterface $task, VersionNumberComparator $vnc = null);

    /**
     * isValid validate a version
     *
     * @param TaskInterface $task
     * @param Versionnable $versionnable
     * @param VersionNumberComparator $vnc
     * @access public
     * @return boolean
     */
    public function isValid(Versionnable $versionnable, TaskInterface $task, VersionNumberComparator $vnc);

    /**
     * update a version
     *
     * @param Versionned $version
     * @access public
     * @return void
     */
    public function update(Versionned $version);
    
    /**
     * delete a version
     *
     * @param Versionned $version
     * @access public
     * @return void
     */
    public function delete(Versionned $version);
}
