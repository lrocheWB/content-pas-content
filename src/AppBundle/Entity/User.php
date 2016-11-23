<?php
namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author bpiscart
 */

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserRepository")
 * @ORM\Table(name="tm_user")
 */
class User {
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;
    /**
     * @ORM\Column(type="string", length=255)
     */    
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $isEnabled;    
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;    
    
    /**
     * @ORM\OneToMany(targetEntity="Rate", mappedBy="user")
     */
    private $rates;

    
    /** @ORM\PreUpdate() */
    public function preUpdate()
    {
    }    
    
    
    public function __construct()
    {
        $this->rates = new ArrayCollection();
    }    
    
    
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Feature", inversedBy="users")
     * @ORM\JoinColumn(name="feature_id", referencedColumnName="id")
     */
    private $feature;        
    

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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
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
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
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
     * Set token
     *
     * @param string $token
     *
     * @return User
     */
    public function setToken($token)
    {
        if($this->token == ''){
            $this->token =  password_hash('s@lt_&_p3p3r', PASSWORD_BCRYPT, array('cost' => 12));    
        }
        
        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Add rate
     *
     * @param \AppBundle\Entity\Rate $rate
     *
     * @return User
     */
    public function addRate(\AppBundle\Entity\Rate $rate)
    {
        $this->rates[] = $rate;

        return $this;
    }

    /**
     * Remove rate
     *
     * @param \AppBundle\Entity\Rate $rate
     */
    public function removeRate(\AppBundle\Entity\Rate $rate)
    {
        $this->rates->removeElement($rate);
    }

    /**
     * Get rates
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRates()
    {
        return $this->rates;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
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
     * Set isEnabled
     *
     * @param boolean $isEnabled
     *
     * @return User
     */
    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    /**
     * Get isEnabled
     *
     * @return boolean
     */
    public function getIsEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * Set feature
     *
     * @param \AppBundle\Entity\Feature $feature
     *
     * @return User
     */
    public function setFeature(\AppBundle\Entity\Feature $feature = null)
    {
        $this->feature = $feature;

        return $this;
    }

    /**
     * Get feature
     *
     * @return \AppBundle\Entity\Feature
     */
    public function getFeature()
    {
        return $this->feature;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
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
}
