<?php
namespace App\CoreBundle\Traits\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * abstract class for position attributs.
 * 
 * @category   Generalisation
 * @package    Trait
 * @subpackage Entity
 * @abstract
 */
trait TraitPosition
{
    /**
     * @ORM\Column(name="position", type="integer",  nullable=true)
     */
    protected $position;

    /**
     * @inheritdoc}
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }
    
    /**
     * @inheritdoc}
     */
    public function getPosition()
    {
        return $this->position;
    }
}
