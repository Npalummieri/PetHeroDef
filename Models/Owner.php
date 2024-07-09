<?php

namespace Models;

class Owner extends User{

    private $ownerCode;
	private $suspensionDate;

    
    public function __construct()
    {
        parent::__construct();
    }

    

    /**
     * Get the value of ownerCode
     */ 
    public function getOwnerCode()
    {
        return $this->ownerCode;
    }

    /**
     * Set the value of ownerCode
     *
     * @return  self
     */ 
    public function setOwnerCode($ownerCode)
    {
        $this->ownerCode = $ownerCode;

        return $this;
    }
	
	/**
     * Get the value of ownerCode
     */ 
    public function getSuspensionDate()
    {
        return $this->suspensionDate;
    }

    /**
     * Set the value of ownerCode
     *
     * @return  self
     */ 
    public function setSuspensionDate($suspensionDate)
    {
        $this->suspensionDate = $suspensionDate;

        return $this;
    }

    public function fromUserToOwner(User $user)
    {
        $this->setEmail($user->getEmail());
        $this->setUserName($user->getUserName());
        $this->setPassword($user->getPassword());
        $this->setStatus($user->getStatus());
        $this->setName($user->getName());
        $this->setLastname($user->getLastname());
        $this->setDni($user->getDni());
        $this->setPfp($user->getPfp());
		$this->setBio($user->getBio());
		$this->setSuspensionDate(null);
		
    }
}

?>