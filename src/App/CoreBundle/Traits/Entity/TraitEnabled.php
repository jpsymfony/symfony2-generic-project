<?php
namespace App\CoreBundle\Traits\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * abstract class for enabled attributs.
 * 
 * @category   Generalisation
 * @package    Trait
 * @subpackage Entity
 * @abstract
 */
trait TraitEnabled
{
    /**
     * @var boolean $enabled
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=true)
     */
    protected $enabled;
    
    /**
     * @inheritdoc}
     */
    public function setEnabled($enabled)
    {
        $this->enabled = (Boolean) $enabled;

        return $this;
    }
    
    /**
     * @inheritdoc}
     */
    public function getEnabled()
    {
        return $this->enabled;
    }
}
