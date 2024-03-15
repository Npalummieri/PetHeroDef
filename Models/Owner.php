<?php

namespace Models;

class Owner extends User{

    private $ownerCode;
    private $listPets;
    
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


    /// ---Ver si tengo que usar array/list/etc
    /**
     * Get the value of listPets
     */ 
    public function getListPets()
    {
        return $this->listPets;
    }

    /**
     * Set the value of listPets
     *
     * @return  self
     */ 
    public function setListPets($listPets)
    {
        $this->listPets = $listPets;

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
    }
}

?>