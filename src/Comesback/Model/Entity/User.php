<?php

namespace Comesback\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
 
/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @ORM\HasLifecycleCallbacks()
 */
class User
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
    protected $email;
    
    /** 
     * @ORM\Column(type="string", name="email_private") 
     */
    protected $emailPrivat;
    
    /** 
     * @ORM\Column(type="string") 
     */
    protected $firstname;
    
    /** 
     * @ORM\Column(type="string") 
     */
    protected $lastname;
    
    /** 
     * @ORM\Column(type="string") 
     */
    protected $avatar;
    
    /** 
     * @ORM\Column(type="string") 
     */
    protected $password;
    
    /** 
     * @ORM\Column(type="string") 
     */
    protected $salt;
    
    /** 
     * @ORM\Column(type="integer", name="company_id") 
     */
    protected $companyId;
    
    /** 
     * @ORM\Column(type="integer", name="position_id") 
     */
    protected $positionId;
    
    /** 
     * @ORM\Column(type="boolean", name="is_active") 
     */
    protected $isActive;
    
    /** 
     * @ORM\Column(type="boolean", name="is_admin") 
     */
    protected $isAdmin;
    
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
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set emailPrivat
     *
     * @param string $emailPrivat
     */
    public function setEmailPrivat($emailPrivat)
    {
        $this->emailPrivat = $emailPrivat;
    }

    /**
     * Get emailPrivat
     *
     * @return string 
     */
    public function getEmailPrivat()
    {
        return $this->emailPrivat;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * Get avatar
     *
     * @return string 
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set companyId
     *
     * @param integer $companyId
     */
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;
    }

    /**
     * Get companyId
     *
     * @return integer 
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * Set positionId
     *
     * @param integer $positionId
     */
    public function setPositionId($positionId)
    {
        $this->positionId = $positionId;
    }

    /**
     * Get positionId
     *
     * @return integer 
     */
    public function getPositionId()
    {
        return $this->positionId;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set isAdmin
     *
     * @param boolean $isAdmin
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }

    /**
     * Get isAdmin
     *
     * @return boolean 
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return datetime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get updatedAt
     *
     * @return datetime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}