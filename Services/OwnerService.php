<?php

namespace Services;


use \Exception as Exception;
use Models\Owner as Owner;
use DAO\OwnerDAO as OwnerDAO;
use DAO\KeeperDAO as KeeperDAO;
use Models\Status as Status;
use Utils\Dates as Dates;



class OwnerService
{

    private $ownerDAO;
    private $keeperDAO;


    public function __construct()
    {
        $this->ownerDAO = new OwnerDAO();
        $this->keeperDAO = new KeeperDAO();
    }

    public function generateCode()
    {
        $uuid = uniqid('OWN', true);

        return $uuid;
    }

    public function srv_add(Owner $owner, $userInfo)
    {
        $errorMsge = 1;

        try {
            $ownerCode = $this->generateCode();
            $owner->setOwnerCode($ownerCode);

            $resultCode = $this->ownerDAO->Add($owner);


            if ($resultCode  != null) {
                $updatepfp = $this->ownerDAO->updatePfp($resultCode, $userInfo["pathToDB"]);

                if ($updatepfp == 1) {
                    move_uploaded_file($userInfo["pfp"], $userInfo["pathToSave"]);
                } else {
                    $errorMsge = "Something's wrong with the pfp,already register";
                }
            } else {
                $errorMsge = "Failed register error in DB.Contact support";
            }
        } catch (Exception $ex) {
            $errorMsge .=  $ex->getMessage();
        }
        return $errorMsge;
    }

    public function getByCode($code)
    {
        $ownerSearched = $this->ownerDAO->searchByCode($code);

        return $ownerSearched;
    }

    public function getAllKeepers()
    {
        try {
            $arrayKeepers = $this->keeperDAO->GetAll();
        } catch (Exception $ex) {
            $arrayKeepers = $ex->getMessage();
        }
        return $arrayKeepers;
    }

    public function getAllInfoKeepers()
    {
        try {
            $arrayKeepers = $this->keeperDAO->getKeeperFullInfo();
        } catch (Exception $ex) {
            $arrayKeepers = $ex->getMessage();
        }
        return $arrayKeepers;
    }

    public function srv_updateOwner($ownerLogged, $pfpInfo, $bio)
    {
        try {
            $error = 1;

            $ownerSearched = $this->ownerDAO->searchByCode($ownerLogged);
            $pfpToDelete = $ownerSearched->getPfp();
            if (isset($pfpInfo["pfp"]["tmp_name"]) && !empty($pfpInfo["pfp"]["tmp_name"])) {

                $nameFile = $pfpInfo["pfp"]["name"];
                $imgSize = $pfpInfo["pfp"]["size"];
                $typeImg = $pfpInfo["pfp"]["type"];
                $dim = getimagesize($pfpInfo["pfp"]["tmp_name"]);
                $width = $dim[0];
                $height = $dim[1];
                $extension = explode(".", $nameFile);


                $admittedTypes = ["image/jpg", "image/png", "image/jpeg", "image/bmp", "image/gif"];

                
                $mime = mime_content_type($pfpInfo["pfp"]["tmp_name"]);

                if (!in_array($mime, $admittedTypes)) {
                    $error = "Error with your photo,check the selected file";
                } else if ($imgSize > 3 * 1024 * 1024) {
                    $error = "Not supported size";
                } else {

                    $name_pfp = $pfpInfo["pfp"]["name"];
                   
                    $pfp = $pfpInfo["pfp"]["tmp_name"];


                    //hasheo el archivo
                    $hashedNameFile = hash_file('sha1', $pfp);
                    $pathToSave =  PFP_OWNERS . $hashedNameFile . '.' . $extension[1];
                    //Ruta guardada en BD de la ruta 
                    $pathToBD = "PFPOwners/" . $hashedNameFile . '.' . $extension[1];

                    $result = $this->ownerDAO->updatePfp($ownerLogged, $pathToBD);
                    if ($result == 1) {
                        move_uploaded_file($pfp, $pathToSave);
                        unlink(IMG_PATH . $pfpToDelete);
                    }
                }
            }

            if (isset($bio) && !empty($bio) && $bio != null) {
                if (preg_match('/[^a-z0-9!.,?=$]/i', $bio)) {
                    // Si la expresión regular encuentra algún caracter que no sea letra, dígito o signo de puntuación básico, la función devuelve false
                    $error = "error at bio";
                } else {
                    $error = $this->ownerDAO->updateBio($ownerLogged, $bio);
                }
            }
        } catch (Exception $ex) {
            $error =  $ex->getMessage();
        }
        return $error;
    }
	
	public function srv_getAllOwners()
	{
	try{
		$arrayOwns = $this->ownerDAO->GetAll();
	}catch(Exception $ex)
	{
		$arrayOwns = $ex->getMessage();
	}
	return $arrayOwns;
	}
	
	public function srv_editEmail($ownerCode,$email)
	{
		try{
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
				$resp = "This type not allowed";
			}else{
				$resp = $this->ownerDAO->updateEmail($ownerCode,$email);
			}
			
			if($resp != 1)
			{
				$resp = "Not modified email";
			}
		}catch(Exception $ex)
		{
			$resp = $ex->getMessage();
		}
		return $resp;
	}
	
	public function srv_editUsername($ownerCode,$username)
	{
		try{
			$regexUsername = "/^(?=.*[A-Za-z])(?!.*[\s!@])(?:\D*\d){0,4}[A-Za-z\d]{6,20}$/";
			if (preg_match($regexUsername, $username)) {

                    $username = trim($username);
					$resp = $this->ownerDAO->updateUsername($ownerCode,$username);
			}else{
				$resp = "Username doesn't match the requirements!!";
			}
			if($resp != 1)
			{
				$resp = "Not modified username"; 
			}
		}catch(Exception $ex)
		{
			$resp = $ex->getMessage();
		}
		return $resp;
	}
	
public function srv_editStatus($ownerCode, $status)
{
    try {

        if ($status != Status::ACTIVE && $status != Status::INACTIVE && $status != Status::SUSPENDED) {
            $resp = "Not permitted status";
        } else {
            
            $resp = $this->ownerDAO->updateStatus($ownerCode, $status);
            
            
            if ($resp !== 1) {
                $resp = "Not modified status";
            } 
        }
    } catch (Exception $ex) {

        $resp = $ex->getMessage();
    }
    
    return $resp;
}

	
	public function srv_editName($ownerCode,$name)
	{
		try{
			$name_alpha_spaces = ctype_alpha(str_replace(' ', '', $name));
            if ($name_alpha_spaces) {
                $resp = $this->ownerDAO->updateName($ownerCode,$name);
			}else{
				$resp = "name doesn't match the requirements!!";
			}
			if($resp != 1)
			{
				$resp = "Not modified name"; 
			}
		}catch(Exception $ex)
		{
			$resp = $ex->getMessage();
		}
		return $resp;
	}
	
	public function srv_editLastname($ownerCode,$lastname)
	{
		try{
			$name_alpha_spaces = ctype_alpha(str_replace(' ', '', $lastname));
            if ($name_alpha_spaces) {
                $resp = $this->ownerDAO->updatelastname($ownerCode,$lastname);
			}else{
				$resp = "Lastname doesn't match the requirements!!";
			}
			if($resp != 1)
			{
				$resp = "Not modified lastname"; 
			}
		}catch(Exception $ex)
		{
			$resp = $ex->getMessage();
		}
		return $resp;
	}
	
	public function srv_editSuspensionDate($ownerCode,$suspensionDate)
	{
		try{
			if(Dates::validateDate($suspensionDate))
			{
				if(Dates::currentCheck($suspensionDate) == 1)
				{
					$result = $this->ownerDAO->updateSuspDate($ownerCode,$suspensionDate);
					if($result != 1)
					{
						$resp = "Not modified suspension Date";
					}
				}else{
					$resp = "Date older than current";
				}
			}else{
				$resp = "Not valid date";
			}
			
		}catch(Exception $ex)
		{
			$resp = $ex->getMessage();
		}
		return $resp;
	}
	
	public function listOwnerFiltered($code)
	{
        try {
        if (strpos($code, "OWN") !== false || 
            filter_var($code, FILTER_VALIDATE_EMAIL) || 
            (preg_match("/^\d{8}$/",$code) == 1))
			{
				$ownList = $this->ownerDAO->getFilteredOwnsAdm($code);
			}else {
				$ownList = "Not matching results.Remember to use BOOK,OWN,PET or KEP";
				}
        }catch(Exception $ex)
		{
			$ownList = $ex->getMessage();
		}
		return $ownList;
	}
	
	public function srv_deleteOwner($ownerCode)
	{
		try{
			if(strpos($ownerCode,"OWN") !== false)
			{
				$resp =$this->ownerDAO->deleteOwner($ownerCode);
				if($resp != 1)
				{
					$resp = "Not possible to delete the owner,check that doesn't have any booking/coupon in course";
				}
			}else{
				$resp = "Not valid ownerCode";
			}
			
		}catch(Exception $ex)
		{
			$resp = "The owner is still with a booking/coupon in course.Cannot delete ";
		}
		return $resp;
	}
}
