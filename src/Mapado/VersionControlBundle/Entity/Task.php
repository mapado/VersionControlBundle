<?php

namespace Mapado\VersionControlBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mapado\VersionControlBundle\Model\SimpleTask;

/**
 * Task
 * 
 * @author Julien DENIAU <julien.deniau@mapado.com>
 *
 * @ORM\Entity
 * @ORM\Table("task")
 */
class Task extends SimpleTask
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
     * name
     * 
     * @var mixed
     * @access private
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

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
