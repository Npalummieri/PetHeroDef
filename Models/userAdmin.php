<?php 

namespace Models;

class userAdmin extends User{
	
	private $adminCode;
	
	public function __construct()
    {
        parent::__construct();
    }
	
	    /**
     * Get the value of ownerCode
     */ 
    public function getAdminCode()
    {
        return $this->adminCode;
    }

    /**
     * Set the value of ownerCode
     *
     * @return  self
     */ 
    public function setAdminCode($ownerCode)
    {
        $this->adminCode = $adminCode;

        return $this;
    }
}

?>