<?php

namespace Models;

class Review{

    private $codeKeeper;
    private $codeOwner; 
    private $comment;
    private $score;
    private $timeStamp;
    private $codeReview;
    /**
     * Get the value of codeKeeper
     */ 
    public function getCodeKeeper()
    {
        return $this->codeKeeper;
    }

    /**
     * Set the value of codeKeeper
     *
     * @return  self
     */ 
    public function setCodeKeeper($codeKeeper)
    {
        $this->codeKeeper = $codeKeeper;

        return $this;
    }

    /**
     * Get the value of codeOwner
     */ 
    public function getCodeOwner()
    {
        return $this->codeOwner;
    }

    /**
     * Set the value of codeOwner
     *
     * @return  self
     */ 
    public function setCodeOwner($codeOwner)
    {
        $this->codeOwner = $codeOwner;

        return $this;
    }

    /**
     * Get the value of comment
     */ 
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set the value of comment
     *
     * @return  self
     */ 
    public function setComment($comment)
    {
        $this->comment = $comment;

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
     * Get the value of timeStamp
     */ 
    public function getTimeStamp()
    {
        return $this->timeStamp;
    }

    /**
     * Set the value of timeStamp
     *
     * @return  self
     */ 
    public function setTimeStamp($timeStamp)
    {
        $this->timeStamp = $timeStamp;

        return $this;
    }

    /**
     * Get the value of codeReview
     */ 
    public function getCodeReview()
    {
        return $this->codeReview;
    }

    /**
     * Set the value of codeReview
     *
     * @return  self
     */ 
    public function setCodeReview($codeReview)
    {
        $this->codeReview = $codeReview;

        return $this;
    }
}

?>