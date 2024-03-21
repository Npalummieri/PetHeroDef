<?php

namespace Models;

use ReflectionClass;
use ReflectionProperty;

class Keeper extends User{

    private $keeperCode; //unico - PK
    private $typeCare;
    private $price;
    private $typePet;
    private $score;
    private $initDate;
    private $endDate;
    private $visitPerDay;
    //Tengo que agregar puntaje!!

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

    //Automatizacion del pasaje de info de un objeto a otro
    //Visto un par de semanas despues,esta bueno esto de Reflection pero no se si es el proposito...
    //Quizá deberia tener una funcion dentro de la clases hijas (Own,Keep) que reciban el objeto padre y de ahi copiar sus datos direcemtante sin el uso de Reflection
    //Voy a probarlo en owner
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

                // Obtener el valor desde User y establecerlo en Keeper usando getters y setters
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

        echo "VALOR REFLEX";
        var_dump($this);
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