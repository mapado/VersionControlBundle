<?php

namespace Mapado\VersionControlBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mapado\VersionControlBundle\Model;

/**
 * VersionNumber
 * 
 * @author Julien DENIAU <julien.deniau@mapado.com>
 *
 * @ORM\Entity
 * @ORM\Table("version_number")
 */
class VersionNumber extends Model\VersionNumber
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
     * @ORM\Column(name="major", type="integer", length=10)
     */
    protected $major;

    /**
     * {@inheritedDoc}
     *
     * @ORM\Column(name="minor", type="integer", length=10)
     */
    protected $minor;

    /**
     * {@inheritedDoc}
     *
     * @ORM\Column(name="maintenance", type="integer", length=10)
     */
    protected $maintenance;

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
