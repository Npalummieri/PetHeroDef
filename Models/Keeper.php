<?php

namespace Models;

use ReflectionClass;
use ReflectionProperty;

class Keeper extends User{

    private $keeperCode;
    private $typeCare;
    private $price;
    private $typePet;
    private $score;
    private $initDate;
    private $endDate;
    private $visitPerDay;


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get the value of keeperCode
     */ 
    public function getKeeperCode()
    {
        return $this->keeperCode;
    }

    /**
     * Set the value of keeperCode
     *
     * @return  self
     */ 
    public function setKeeperCode($keeperCode)
    {
        $this->keeperCode = $keeperCode;

        return $this;
    }

    /**
     * Get the value of typeCare
     */ 
    public function getTypeCare()
    {
        return $this->typeCare;
    }

    /**
     * Set the value of typeCare
     *
     * @return  self
     */ 
    public function setTypeCare($typeCare)
    {
        $this->typeCare = $typeCare;

        return $this;
    }


    /**
     * Get the value of price
     */ 
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set the value of price
     *
     * @return  self
     */ 
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    //Testing reflection class
    public function fromUserToKeeper(User $user,$typePet,$typeCare,$initDate,$endDate,$price,$visitPerDay)
    {
        $reflexUser = new ReflectionClass($user);
        $reflexKeeper = new ReflectionClass($this);
        
        foreach($reflexUser->getProperties(ReflectionProperty::IS_PRIVATE) as $userProperty)
        {

            $userProperty->setAccessible(true);
           
            $propertyName = $userProperty->getName();
            //echo "<br>$propertyName<br>";

            $setterName = "set". ucfirst($propertyName);
            //echo "<br>$setterName<br>";
            if($reflexKeeper->hasMethod($setterName))
            {
                $getterName = 'get' . ucfirst($propertyName);

                
                $value = $userProperty->getValue($user);
                $this->$setterName($value);
            }
        }

        $this->setTypePet($typePet);
        $this->setTypeCare($typeCare);
        $this->setInitDate($initDate);
        $this->setEndDate($endDate);
        $this->setPrice($price);
        $this->setVisitPerDay($visitPerDay);


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
     * Get the value of score
     */ 
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set the value of score
     *
     * @return  self
     */ 
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get the value of initDate
     */ 
    public function getInitDate()
    {
        return $this->initDate;
    }

    /**
     * Set the value of initDate
     *
     * @return  self
     */ 
    public function setInitDate($initDate)
    {
        $this->initDate = $initDate;

        return $this;
    }

    /**
     * Get the value of endDate
     */ 
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set the value of endDate
     *
     * @return  self
     */ 
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get the value of visitPerDay
     */ 
    public function getVisitPerDay()
    {
        return $this->visitPerDay;
    }

    /**
     * Set the value of visitPerDay
     *
     * @return  self
     */ 
    public function setVisitPerDay($visitPerDay)
    {
        $this->visitPerDay = $visitPerDay;

        return $this;
    }
}


?>