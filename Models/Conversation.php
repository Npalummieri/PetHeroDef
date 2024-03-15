<?php 

namespace Models;

class Conversation{

    private $idCon;
    private $codeConv;
    private $keeperCode;
    private $ownerCode;
    private $timestamp;
    private $status;
    

    /**
     * Get the value of idCon
     */ 
    public function getIdCon()
    {
        return $this->idCon;
    }

    /**
     * Set the value of idCon
     *
     * @return  self
     */ 
    public function setIdCon($idCon)
    {
        $this->idCon = $idCon;

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
     * Get the value of codeConv
     */ 
    public function getCodeConv()
    {
        return $this->codeConv;
    }

    /**
     * Set the value of codeConv
     *
     * @return  self
     */ 
    public function setCodeConv($codeConv)
    {
        $this->codeConv = $codeConv;

        return $this;
    }
}

?>