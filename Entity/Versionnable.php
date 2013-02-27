<?php

namespace Mapado\VersionControlBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mapado\VersionControlBundle\Model\SimpleVersion;

/**
 * Versionnable
 * 
 * @author Julien DENIAU <julien.deniau@mapado.com>
 *
 * @ORM\Entity
 * @ORM\Table("versionnable",
 *      uniqueConstraints={ @ORM\UniqueConstraint(name="version",columns={"version_id", "version_type"}) }
 * )
 */
class Versionnable extends SimpleVersion
{
    /**
     * id
     * 
     * @var mixed
     * @access private
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * {@inheritedDoc}
     *
     * @ORM\Column(name="version_id", type="string", length=255)
     */
    protected $versionId;

    /**
     * {@inheritedDoc}
     *
     * @ORM\Column(name="version_type", type="string", length=255)
     */
    protected $versionType;

    /**
     * getId
     *
     * @access public
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
