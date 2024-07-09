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
				}// } else {
                //     $errorMsge = "Imagen ";
                // }
            } else {
                $errorMsge = "No es posible cargar esta imagen.";
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
                    $error = "Error en el tipo de imagen. JPG,PNG,JPEG,BMP,GIF permitidos.";
                } else if ($imgSize > 3 * 1024 * 1024) {
                    $error = "Tamaño no soportado. 5MB max.";
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
                    $error = "Unicamente valores alfanumericos. Caracteres especiales no permitidos";
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
				$resp = "Email invalido.";
			}else{
				$resp = $this->ownerDAO->updateEmail($ownerCode,$email);
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
				$resp = "Nombre de usuario no respeta los requisitos.";
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
            $resp = "Estado invalido. Verifique";
        } else {
            $resp = $this->ownerDAO->updateStatus($ownerCode, $status);
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
				$resp = "Nombre no cumple los requisitos.";
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
				$resp = "Apellido no cumple los requisitos.";
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
				}else{
					$resp = "Fecha ya pasada.";
				}
			}else{
				$resp = "Fecha invalida. Revise";
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
				$ownList = "No hubo coincidencias. Recuerde usar BOOK,OWN,PET o KEP";
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
				$resp =$this->ownerDAO->delete($ownerCode);
			}else{
				$resp = "Codigo invalido.";
			}
			
		}catch(Exception $ex)
		{
			$resp = "No es posible borrar el dueño actualmente.";
		}
		return $resp;
	}
}
