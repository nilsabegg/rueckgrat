<?php

namespace Comesback\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
 
/**
 * @ORM\Entity
 * @ORM\Table(name="idea")
 * @ORM\HasLifecycleCallbacks()
 */
class Idea
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
    protected $text;
    
    /** 
     * @ORM\Column(type="integer", name="user_id") 
     */
    protected $userId;
    
    /** 
     * @ORM\Column(type="boolean", name="is_deleted") 
     */
    protected $isDeleted;
    
    /** 
     * @ORM\Column(type="datetime", name="created_at") 
     */
    protected $createdAt;
    
    /** 
     * @ORM\Column(type="datetime", name="updated_at") 
     */
    protected $updatedAt;
    
    /**
     * Constructor
     */
    public function __construct()
    {

    }
    
    /**
     * @ORM\PrePersist
     */
    public function setDefaultValues() 
    {
        if ($this->createdAt == null) {
            $this->createdAt = new \DateTime();
        }
        else {
            $this->updatedAt = new \DateTime();
        }
        
    }
}
