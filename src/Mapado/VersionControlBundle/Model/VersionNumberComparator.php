<?php

namespace Mapado\VersionControlBundle\Model;

/**
 * VersionNumber
 * 
 * @author Julien DENIAU <julien.deniau@mapado.com>
 */
class VersionNumberComparator
{
    /**
     * acceptedOperator
     * 
     * @var array
     * @access protected
     */
    protected $acceptedOperatorList = array(
        '>', '>=', '<', '<=', '=', '!='
    );

    /**
     * versionNumber
     * 
     * @var VersionNumber
     * @access protected
     */
    protected $versionNumber;

    /**
     * operator comparaison operator
     * 
     * @var string
     * @access protected
     */
    protected $operator;

    /**
     * __construct
     *
     * @param string $version
     * @param string $operator
     * @access public
     * @return void
     */
    public function __construct($version = null, $operator = '=')
    {
        if (isset($version)) {
            if (is_string($version)) {
                $version = new VersionNumber($version);
            }
            $this->setVersionNumber($version);
        }

        $this->setOperator($operator);
    }

    /**
     * Gets the value of versionNumber
     *
     * @return VersionNumber
     */
    public function getVersionNumber()
    {
        return $this->versionNumber;
    }
    
    /**
     * Sets the value of versionNumber
     *
     * @param VersionNumber $versionNumber version number object
     *
     * @return VersionNumberComparator
     */
    public function setVersionNumber(VersionNumber $versionNumber)
    {
        $this->versionNumber = $versionNumber;
        return $this;
    }

    /**
     * Gets the value of operator
     *
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }
    
    /**
     * Sets the value of operator
     *
     * @param string $operator comparaison operator
     *
     * @return VersionNumberComparator
     */
    public function setOperator($operator)
    {
        if (!in_array($operator, $this->acceptedOperatorList)) {
            $msg = 'Operator "' . $operator . '" is not accepted';
            throw new \UnexpectedValueException($msg);
        }
        $this->operator = $operator;
        return $this;
    }
}
