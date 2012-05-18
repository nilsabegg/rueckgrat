<?php

namespace Comesback\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
 
/**
 * @ORM\Entity
 * @ORM\Table(name="user_action")
 * @ORM\HasLifecycleCallbacks()
 */
class UserAction
{
    
    /**
     * @ORM\Id 
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;
    
    /** 
     * @ORM\Column(type="integer", name="user_id") 
     */
    protected $userId;

    /** 
     * @ORM\Column(type="integer", name="idea_id") 
     */
    protected $actionId;
    
    /** 
     * @ORM\Column(type="integer", name="idea_id") 
     */
    protected $ideaId;
    
    /** 
     * @ORM\Column(type="integer", name="idea_comment_id") 
     */
    protected $ideaCommentId;
    
    /** 
     * @ORM\Column(type="integer", name="idea_vote_id") 
     */
    protected $ideaVoteId;
    
    /** 
     * @ORM\Column(type="integer", name="invite_id") 
     */
    protected $inviteId;
    
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