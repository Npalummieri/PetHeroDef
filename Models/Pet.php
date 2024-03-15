<?php

namespace Models;

class Pet{

    private $id;
    private $petCode;
    private $name;
    private $pfp;
    private $ownerCode; //Puede ser el obj owner como tal o su ownerCode;
    private $size;
    private $breed; //La raza la idea es tomarla de una API
    private $vaccPlan; //Imagen
    private $video;
    private $typePet; //Se podria una API de mascotas permitidas o alguna instancia de la misma
    private $age;
    

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of petCode
     */ 
    public function getPetCode()
    {
        return $this->petCode;
    }

    /**
     * Set the value of petCode
     *
     * @return  self
     */ 
    public function setPetCode($petCode)
    {
        $this->petCode = $petCode;

        return $this;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of size
     */ 
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set the value of size
     *
     * @return  self
     */ 
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get the value of breed
     */ 
    public function getBreed()
    {
        return $this->breed;
    }

    /**
     * Set the value of breed
     *
     * @return  self
     */ 
    public function setBreed($breed)
    {
        $this->breed = $breed;

        return $this;
    }

    /**
     * Get the value of vaccPlan
     */ 
    public function getVaccPlan()
    {
        return $this->vaccPlan;
    }

    /**
     * Set the value of vaccPlan
     *
     * @return  self
     */ 
    public function setVaccPlan($vaccPlan)
    {
        $this->vaccPlan = $vaccPlan;

        return $this;
    }

    /**
     * Get the value of video
     */ 
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * Set the value of video
     *
     * @return  self
     */ 
    public function setVideo($video)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * Get the value of typePet
     */ 
    public function getTypePet()
    {
        return $this->typePet;
    }

    /**
     * Set the value of typePet
     *
     * @return  self
     */ 
    public function setTypePet($typePet)
    {
        $this->typePet = $typePet;

        return $this;
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
     * Get the value of pfp
     */ 
    public function getPfp()
    {
        return $this->pfp;
    }

    /**
     * Set the value of pfp
     *
     * @return  self
     */ 
    public function setPfp($pfp)
    {
        $this->pfp = $pfp;

        return $this;
    }


    public function getAge()
    {
        return $this->age;
    }


    public function setAge($age)
    {
        $this->age = $age;
        return $this;
    }
}


?>