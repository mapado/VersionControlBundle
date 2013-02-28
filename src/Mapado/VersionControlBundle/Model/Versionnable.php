<?php

namespace Mapado\VersionControlBundle\Model;

/**
 * Versionnable
 * 
 * @author Julien DENIAU <julien.deniau@mapado.com> 
 */
interface Versionnable
{
    /**
     * getVersionId
     *
     * @access public
     * @return string
     */
    public function getVersionId();

    /**
     * getVersionType
     *
     * @access public
     * @return string
     */
    public function getVersionType();
}
