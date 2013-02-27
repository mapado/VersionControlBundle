<?php

namespace Mapado\VersionControlBundle\Model;

/**
 * VersionNumber
 * 
 * @author Julien DENIAU <julien.deniau@mapado.com>
 */
class VersionNumber implements \JsonSerializable
{
    /**
     * major
     * 
     * @var int
     * @access protected
     */
    protected $major;

    /**
     * minor
     * 
     * @var int
     * @access protected
     */
    protected $minor;

    /**
     * maintenance
     * 
     * @var int
     * @access protected
     */
    protected $maintenance;

    /**
     * __construct
     *
     * @param string $version string version
     * @access public
     * @return void
     */
    public function __construct($version = null)
    {
        if (!empty($version)) {
            $this->createFromString($version);
        }
    }

    /**
     * createFromString
     *
     * @param string $version
     * @access protected
     * @return void
     */
    protected function createFromString($version) {
        $rMajor = '(?<major>[0-9]+)';
        $rMinor = '(?<minor>[0-9]+)';
        $rMaintenance = '(?<maintenance>[0-9]+)';
        $regex = '/^[vV]?' .
                $rMajor .
                '(?:\.' . $rMinor . '(?:\.' . $rMaintenance . ')?)?$/';
        $matches = array();
        $matched = preg_match($regex, trim($version), $matches);

        if ($matched) {
            // major
            $this->setMajor($matches['major']);

            // minor
            if (!isset($matches['minor'])) {
                $matches['minor'] = 0;
            }
            $this->setMinor($matches['minor']);

            // maintenance
            if (!isset($matches['maintenance'])) {
                $matches['maintenance'] = 0;
            }
            $this->setMaintenance($matches['maintenance']);
        } else {
            $msg = 'Unable to create a VersionNumber with "' . $version . '"';
            throw new \UnexpectedValueException($msg);
        }
    }

    /**
     * Gets the value of major
     *
     * @return int
     */
    public function getMajor()
    {
        return $this->major;
    }
    
    /**
     * Sets the value of major
     *
     * @param int $major major number
     *
     * @return VersionNumber
     */
    public function setMajor($major)
    {
        $this->major = $major;
        return $this;
    }

    /**
     * Gets the value of minor
     *
     * @return int
     */
    public function getMinor()
    {
        return $this->minor;
    }
    
    /**
     * Sets the value of minor
     *
     * @param int $minor minor number
     *
     * @return VersionNumber
     */
    public function setMinor($minor)
    {
        $this->minor = $minor;
        return $this;
    }

    /**
     * Gets the value of maintenance
     *
     * @return int
     */
    public function getMaintenance()
    {
        return $this->maintenance;
    }
    
    /**
     * Sets the value of maintenance
     *
     * @param int $maintenance maintenance number
     *
     * @return VersionNumber
     */
    public function setMaintenance($maintenance)
    {
        $this->maintenance = $maintenance;
        return $this;
    }

    /**
     * __toString
     *
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->getMajor() .
            '.' . 
            $this->getMinor() .
            '.' . 
            $this->getMaintenance();
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
            'complete' => (string) $this,
            'major' => $this->getMajor(),
            'minor' => $this->getMinor(),
            'maintenance' => $this->getMaintenance(),
        );
    }

    /**
     * compare
     *
     * @param VersionNumber $a
     * @param VersionNumber $b
     * @static
     * @access public
     * @return int -1 if $a < $b, 0 if $a == $b, 1 if $a > $b
     */
    public static function compare(VersionNumber $a, VersionNumber $b)
    {
        return strnatcmp($a, $b);
    }
}

