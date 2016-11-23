<?php
namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Rate
 *
 * @author bpiscart
 */

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Entity\RateRepository")
 * @ORM\Table(name="tm_rate")
 */
class Rate {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="integer")
     */
    private $rate;
    
    
    
    /**
     * @ORM\Column(type="datetime")
     */    
    private $createdAt;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="rates")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;    
    
    /**
     * @PrePersist
     */
    function onPrePersist() {
        // set default date
        $this->createdAt = new DateTime("now");
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
     * Set rate
     *
     * @param integer $rate
     *
     * @return Rate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return integer
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Rate
     */
    public function setCreatedAt($createdAt)
    {   
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Rate
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
    
    
    
    public static function getLabels()
    {
        $labels = array(
            1 => 'Mauvaises journées',
            2 => 'Journées difficiles',
            3 => 'Journées normales',
            4 => 'Bonnes journées',
            5 => 'Excellentes journées',
        );
        
        return $labels;
    }

    /**
     * Set userId
     *
     * @param \AppBundle\Entity\User $userId
     *
     * @return Rate
     */
    public function setUserId(\AppBundle\Entity\User $userId = null)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return \AppBundle\Entity\User
     */
    public function getUserId()
    {
        return $this->userId;
    }
}
