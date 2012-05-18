<?php

namespace Comesback\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
 
/**
 * @ORM\Entity
 * @ORM\Table(name="action")
 */
class Action
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
    
    /** 
     * @ORM\Column(type="integer") 
     */
    protected $credits;
    

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set credits
     *
     * @param integer $credits
     */
    public function setCredits($credits)
    {
        $this->credits = $credits;
    }

    /**
     * Get credits
     *
     * @return integer 
     */
    public function getCredits()
    {
        return $this->credits;
    }
}