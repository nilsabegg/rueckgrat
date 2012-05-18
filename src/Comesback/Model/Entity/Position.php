<?php

namespace Comesback\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
 
/**
 * @ORM\Entity
 * @ORM\Table(name="position")
 */
class Position
{
    
    /**
     * @ORM\Id 
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;
    
    /** 
     * @ORM\Column(type="string") 
     */
    protected $name;
    
}