<?php

namespace Models;

class Coupon{

    private $id;
    private $couponCode;
    private $bookCode; 
    private $price; 
    private $status;

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
     * Get the value of couponCode
     */ 
    public function getCouponCode()
    {
        return $this->couponCode;
    }

    /**
     * Set the value of couponCode
     *
     * @return  self
     */ 
    public function setCouponCode($couponCode)
    {
        $this->couponCode = $couponCode;

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
}

?>