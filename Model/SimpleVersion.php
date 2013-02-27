<?php

namespace Mapado\VersionControlBundle\Model;

class SimpleVersion implements Versionnable, \JsonSerializable
{
    /**
     * versionId
     * 
     * @var [string, int, float]
     * @access protected
     */
    protected $versionId;

    /**
     * versionType
     * 
     * @var string
     * @access protected
     */
    protected $versionType;

    /**
     * __construct
     *
     * @param [string, int, float] $id
     * @param string $type
     * @access public
     * @return void
     */
    public function __construct($id = null, $type = null)
    {
        if (isset($id) && isset($type)) {
            $this->setVersionId($id);
            $this->setVersionType($type);
        } elseif (isset($id) || isset($type)) {
            $msg = 'You can either pass both or no arguments';
            throw new \InvalidArgumentException($msg);
        }
    }
    
    /**
     * Gets the value of versionId
     *
     * @return scalar
     */
    public function getVersionId()
    {
        return $this->versionId;
    }
    
    /**
     * Sets the value of versionId
     *
     * @param scalar $versionId version id
     *
     * @return SimpleVersion
     */
    public function setVersionId($versionId)
    {
        if (!is_scalar($versionId) || is_bool($versionId) === true) {
            $msg = 'Version Id must be string, int or float';
            throw new \InvalidArgumentException($msg);
        }
        $this->versionId = $versionId;
        return $this;
    }

    /**
     * Gets the value of versionType
     *
     * @return string
     */
    public function getVersionType()
    {
        return $this->versionType;
    }
    
    /**
     * Sets the value of versionType
     *
     * @param string $versionType version type
     * (ex: mapado.activity, website.url, wikipedia.article)
     *
     * @return SimpleVersion
     */
    public function setVersionType ($versionType)
    {
        $this->versionType = (string) $versionType;
    }

    /**
     * jsonSerialize
     *
     * @access public
     * @return string
     */
    public function jsonSerialize()
    {
        return array(
            'id' => (string) $this->getVersionId(),
            'type' => $this->getVersionType()
        );
    }
}
