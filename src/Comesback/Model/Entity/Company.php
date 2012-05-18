<?php

namespace Comesback\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
 
/**
 * @ORM\Entity
 * @ORM\Table(name="company")
 * @ORM\HasLifecycleCallbacks()
 */
class Company
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
     * @ORM\Column(type="string") 
     */
    protected $domain;
    
    /** 
     * @ORM\Column(type="string", name="top_level_domain") 
     */
    protected $topLevelDomain;
    
    /** 
     * @ORM\Column(type="string") 
     */
    protected $subdomain;
    
    /** 
     * @ORM\Column(type="string") 
     */
    protected $website;
    
    /** 
     * @ORM\Column(type="string") 
     */
    protected $logo;
    
    /** 
     * @ORM\Column(type="string", name="sector_id") 
     */
    protected $sectorId;
    
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
