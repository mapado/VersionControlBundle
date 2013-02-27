<?php

namespace Mapado\VersionControlBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mapado\VersionControlBundle\Model;

/**
 * Versionned
 * 
 * @author Julien DENIAU <julien.deniau@mapado.com>
 *
 * @ORM\Entity
 * @ORM\Table("versionned",
 *      uniqueConstraints={ @ORM\UniqueConstraint(name="versionnable_task",columns={"versionnable_id", "task_id"}) }
 * )
 */
class Versionned extends Model\Versionned
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
     * @ORM\ManyToOne(targetEntity="VersionNumber")
     */
    protected $versionNumber;

    /**
     * {@inheritedDoc}
     *
     * @ORM\ManyToOne(targetEntity="Versionnable")
     */
    protected $versionnable;

    /**
     * {@inheritedDoc}
     *
     * @ORM\ManyToOne(targetEntity="Task")
     */
    protected $task;

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
