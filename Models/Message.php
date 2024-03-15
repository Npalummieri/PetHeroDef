<?php

namespace Models;

class Message{

    private $idMsg;
    private $codeSender;
    private $codeReceiver;
    private $chatCode;
    private $msgText;
    private $timestamp;
    private $seen;

    

    /**
     * Get the value of idMsg
     */ 
    public function getIdMsg()
    {
        return $this->idMsg;
    }

    /**
     * Get the value of msgText
     */ 
    public function getMsgText()
    {
        return $this->msgText;
    }

    /**
     * Set the value of msgText
     *
     * @return  self
     */ 
    public function setMsgText($msgText)
    {
        $this->msgText = $msgText;

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
     * Get the value of codeSender
     */ 
    public function getCodeSender()
    {
        return $this->codeSender;
    }

    /**
     * Set the value of codeSender
     *
     * @return  self
     */ 
    public function setCodeSender($codeSender)
    {
        $this->codeSender = $codeSender;

        return $this;
    }

    /**
     * Get the value of codeReceiver
     */ 
    public function getCodeReceiver()
    {
        return $this->codeReceiver;
    }

    /**
     * Set the value of codeReceiver
     *
     * @return  self
     */ 
    public function setCodeReceiver($codeReceiver)
    {
        $this->codeReceiver = $codeReceiver;

        return $this;
    }

    /**
     * Get the value of chatCode
     */ 
    public function getChatCode()
    {
        return $this->chatCode;
    }

    /**
     * Set the value of chatCode
     *
     * @return  self
     */ 
    public function setChatCode($chatCode)
    {
        $this->chatCode = $chatCode;

        return $this;
    }

    /**
     * Get the value of seen
     */ 
    public function getSeen()
    {
        return $this->seen;
    }

    /**
     * Set the value of seen
     *
     * @return  self
     */ 
    public function setSeen($seen)
    {
        $this->seen = $seen;

        return $this;
    }

    /**
     * Set the value of idMsg
     *
     * @return  self
     */ 
    public function setIdMsg($idMsg)
    {
        $this->idMsg = $idMsg;

        return $this;
    }
}
?>