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
    private $status;
    private $totalPrice;
    private $totalDays;
    private $visitPerDay;
    private $timestamp;



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
     * Get the value of totalDays
     */ 
    public function getTotalDays()
    {
        return $this->totalDays;
    }

    /**
     * Set the value of totalDays
     *
     * @return  self
     */ 
    public function setTotalDays($totalDays)
    {
        $this->totalDays = $totalDays;

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

    /**
     * Get the value of timestamp
     */ 
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set the value of timestamp
     *
     * @return  self
     */ 
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }
}

?>