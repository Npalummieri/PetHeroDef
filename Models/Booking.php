<?php

namespace Models;

class Booking{

    private $id;
    private $bookCode;
    private $ownerCode;
    private $keeperCode;
    private $petCode;
    private $initDate;
    private $endDate;
    private $initHour;
    private $endHour;
    private $status;
    private $totalPrice;


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
     * Get the value of bookCode
     */ 
    public function getBookCode()
    {
        return $this->bookCode;
    }

    /**
     * Set the value of bookCode
     *
     * @return  self
     */ 
    public function setBookCode($bookCode)
    {
        $this->bookCode = $bookCode;

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
     * Get the value of totalPrice
     */ 
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * Set the value of totalPrice
     *
     * @return  self
     */ 
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;

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
     * Get the value of initHour
     */ 
    public function getinitHour()
    {
        return $this->initHour;
    }

    /**
     * Set the value of initHour
     *
     * @return  self
     */ 
    public function setinitHour($initHour)
    {
        $this->initHour = $initHour;

        return $this;
    }

    /**
     * Get the value of status
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */ 
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of endHour
     */ 
    public function getEndHour()
    {
        return $this->endHour;
    }

    /**
     * Set the value of endHour
     *
     * @return  self
     */ 
    public function setEndHour($endHour)
    {
        $this->endHour = $endHour;

        return $this;
    }
}

?>