<?php

namespace Services;

use \Exception as Exception;
use DAO\KeeperDAO as KeeperDAO;
use \DateInterval as DateInterval;
use \DatePeriod as DatePeriod;
use \DateTime as DateTime;
use Models\Keeper as Keeper;
use Models\User as User;
use Utils\Dates as Dates;
use Models\Size as Size;
use Models\Status as Status;

class KeeperService
{

    private $keeperDAO;


    public function __construct()
    {
        $this->keeperDAO = new KeeperDAO();
    }


    public function generateCode()
    {

        $uuid = uniqid('KEP', true);


        return $uuid;
    }


    public function validateKeeperFields($userInfo, $typePet, $typeCare, $initDate, $endDate, $price, $visitPerDay) //valida los campos y retorna ya no el user sino un Keeper
    {
        try {
            $msgeError = 1;
            if (isset($typeCare)) {
                if ($typeCare != Size::BIG) {
                    if ($typeCare != Size::MEDIUM) {
                        if ($typeCare != Size::SMALL) {
                            $msgeError = "Not size allowed";
                        }
                    }
                }
            } else {
                $msgeError = "Null typeCare";
            }

            if (isset($typePet)) {
                if ($typePet != "dog" && $typePet != "cat") {
                    $msgeError = "And that pet is a?";
                }
            }

            if (isset($price)) {
                if ($price < 1) {
                    $msgeError = "Nothing for free";
                }
            }

            if (!($visitPerDay == 1 || $visitPerDay == 2)) {
                $msgeError = "Not valid amount of day to visit";
            }


            if ((Dates::validateDate($initDate) != null && Dates::validateDate($endDate) != null) && (Dates::currentCheck($initDate) && Dates::currentCheck($endDate))
                && (Dates::validateAndCompareDates($initDate, $endDate) >= 0)
            ) {


                $validateDates = Dates::validateAndCompareDates($initDate, $endDate);
            } else {
                $msgeError = "Something wrong with the dates";
            }


            $keeper = new Keeper();

            if (is_string($msgeError)) {
                $keeper = $msgeError;
            } else {
                if ($userInfo["user"] instanceof User) {
                    if ($validateDates >= 0) {
                        $keeper = $keeper->fromUserToKeeper($userInfo["user"], $typePet, $typeCare, $initDate, $endDate, $price, $visitPerDay);
                        $keeper->setKeeperCode($this->generateCode());
                        $keeperCode = $this->keeperDAO->Add($keeper);
                    }

                    if ($keeperCode != null && $keeperCode != " ") {
                        move_uploaded_file($userInfo["pfp"], $userInfo["pathToSave"]);
                        $this->keeperDAO->updatePfp($keeperCode, $userInfo["pathToDB"]);
                    }
                }
            }
        } catch (Exception $ex) {
            //keeper takes the error msge
            $keeper = $msgeError . ' ' . $ex->getMessage();
        }
        return $keeper;
    }

    public function srv_updateKeeper($keeperLogged, $email, $pfpInfo, $bio, $price, $visitPerDay)
    {
        try {
            $error = 1;

            $keeperSearched = $this->keeperDAO->searchByKeeperCode($keeperLogged->getKeeperCode());
            $pfpToDelete = $keeperSearched->getPfp();
            if (isset($pfpInfo["pfp"]["tmp_name"]) && !empty($pfpInfo["pfp"]["tmp_name"])) {

                $nameFile = $pfpInfo["pfp"]["name"];
                $imgSize = $pfpInfo["pfp"]["size"];
                $typeImg = $pfpInfo["pfp"]["type"];
                $dim = getimagesize($pfpInfo["pfp"]["tmp_name"]);
                $width = $dim[0];
                $height = $dim[1];
                $extension = explode(".", $nameFile);


                $admittedTypes = ["image/jpg", "image/png", "image/jpeg", "image/bmp", "image/gif"];

                //MIME : ID format
                $mime = mime_content_type($pfpInfo["pfp"]["tmp_name"]);

                if (!in_array($mime, $admittedTypes)) {
                    $error = "Error with your photo,check the selected file";
                } else if ($imgSize > 3 * 1024 * 1024) {
                    $error = "Not supported size";
                } else {
                    //(filename client-side)
                    $name_pfp = $pfpInfo["pfp"]["name"];

                    //temporal path (file)
                    $pfp = $pfpInfo["pfp"]["tmp_name"];


                    //hash
                    $hashedNameFile = hash_file('sha1', $pfp);
                    $pathToSave =  PFP_KEEPERS . $hashedNameFile . '.' . $extension[1];
                    //path en BD 
                    $pathToBD = "PFPKeepers/" . $hashedNameFile . '.' . $extension[1];
                    $result = $this->keeperDAO->updatePfp($keeperLogged->getKeeperCode(), $pathToBD);
                    if ($result == 1) {
                        move_uploaded_file($pfp, $pathToSave);
                        if (unlink(IMG_PATH . $pfpToDelete)) {
                            $error = "File deleted successfully.";
                        }
                    }
                }


                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $msgeError = "Not an Email";
                } else {
                    $email = trim($email);
                    $this->keeperDAO->updateEmail($keeperLogged->getKeeperCode(), $email);
                }

                if (isset($bio) && !empty($bio) && $bio != null) {
                    if (preg_match('/[^a-z0-9!.,?=$]/i', $bio)) {
                        // Si la expresión regular encuentra algún caracter que no sea letra, dígito o signo de puntuación básico, la función devuelve false
                        $error = "Error at bio";
                    } else {
                        $this->keeperDAO->updateBio($keeperLogged->getKeeperCode(), $bio);
                    }
                }

                if ($price != "") {
                    if ($price > 0) {
                        $this->keeperDAO->updatePrice($keeperLogged->getKeeperCode(), $price);
                    } else {
                        $error  = "Something's wrong with price";
                    }
                }

                if ($visitPerDay == 1 || $visitPerDay == 2) {
                    $this->keeperDAO->updateVisitDay($keeperLogged->getKeeperCode(), $visitPerDay);
                } else {
                    $error = "Not valid amount of day to visit";
                }
            }
        } catch (Exception $ex) {
            $error =  $ex->getMessage();
        }
        return $error;
    }

    public function srv_getKeeperByCode($keeperCode)
    {
        try {
            $keeper = $this->keeperDAO->searchByKeeperCode($keeperCode);
        } catch (Exception $ex) {
            $keeper =  $ex->getMessage();
        }

        return $keeper;
    }

    public function srv_getIntervalDates($keeperCode)
    {
        if (strpos($keeperCode, "KEP") !== false) {
            $dates = $this->keeperDAO->getDatesByCode($keeperCode);
        }

        //Generate Interval

        $intervalDates = array();

        // Convertir las fechas a objetos DateTime
        $initDateDT = new DateTime($dates["initDate"]);
        $endDateDT = new DateTime($dates["endDate"]);

        // Agregar un día al rango de fechas para incluir la fecha final
        $endDateDT->modify('+1 day');

        // Iterar sobre el intervalo de fechas y agregarlas al array
        $intervalObj = new DateInterval('P1D'); // Intervalo de 1 día
        $datePeriodObj = new DatePeriod($initDateDT, $intervalObj, $endDateDT);
        foreach ($datePeriodObj as $date) {
            $intervalDates[] = $date->format('Y-m-d'); // Formato YYYY-MM-DD
        }

        return $intervalDates;
    }

    public function srv_updateAvailability($keeperCode, $initDate, $endDate)
    {
        try {
            $result = Dates::validateAndCompareDates($initDate, $endDate);
            if ($result == 1 || $result == 0) {
                if (Dates::currentCheck($initDate) != null &&  Dates::currentCheck($endDate) != null) {
                    $result = $this->keeperDAO->updateAvailability($keeperCode, $initDate, $endDate);
                } else {
                    $result = "Not possible this date at this time";
                }
            } else {
                $result = "Not valid dates";
            }
        } catch (Exception $ex) {
            $result .=  $ex->getMessage();
        }
        return $result;
    }

    public function srv_getDates($keeperCode)
    {
        $dates = $this->keeperDAO->getDatesByCode($keeperCode);

        return $dates;
    }
	
	public function srv_getAllKeepers()
	{
		try{
			$keepList = $this->keeperDAO->GetAll();
		}catch(Exception $ex)
		{
			$keepList = $ex->getMessage();
		}
		return $keepList;
		
	}
	
		public function srv_editEmail($keeperCode,$email)
	{
		try{
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
				$resp = "This type not allowed";
			}else{
				$resp = $this->keeperDAO->updateEmail($keeperCode,$email);
			}
			
			if($resp != 1)
			{
				$resp = "Not allowed email,try another one";
			}
		}catch(Exception $ex)
		{
			$resp = $ex->getMessage();
		}
		return $resp;
	}
	
	public function srv_editUsername($keeperCode,$username)
	{
		try{
			$regexUsername = "/^(?=.*[A-Za-z])(?!.*[\s!@])(?:\D*\d){0,4}[A-Za-z\d]{6,20}$/";
			if (preg_match($regexUsername, $username)) {

                    $username = trim($username);
					$resp = $this->keeperDAO->updateUsername($keeperCode,$username);
			}else{
				$resp = "Username doesn't match the requirements!!";
			}
			if($resp != 1)
			{
				$resp = "Not allowed username"; 
			}
		}catch(Exception $ex)
		{
			$resp = $ex->getMessage();
		}
		return $resp;
	}
	
public function srv_editStatus($keeperCode, $status)
{
    try {

        if ($status != Status::ACTIVE && $status != Status::INACTIVE && $status != Status::SUSPENDED) {
            $resp = "Not permitted status";
        } else {
            
            $resp = $this->keeperDAO->updateStatus($keeperCode, $status);
            
            
            if ($resp !== 1) {
                $resp = "Not modified status";
            } 
        }
    } catch (Exception $ex) {

        $resp = $ex->getMessage();
    }
    
    return $resp;
}

	
	public function srv_editName($keeperCode,$name)
	{
		try{
			$name_alpha_spaces = ctype_alpha(str_replace(' ', '', $name));
            if ($name_alpha_spaces) {
                $resp = $this->keeperDAO->updateName($keeperCode,$name);
			}else{
				$resp = "name doesn't match the requirements!!";
			}
			if($resp != 1)
			{
				$resp = "Not allowed name"; 
			}
		}catch(Exception $ex)
		{
			$resp = $ex->getMessage();
		}
		return $resp;
	}
	
	public function srv_editLastname($keeperCode,$lastname)
	{
		try{
			$name_alpha_spaces = ctype_alpha(str_replace(' ', '', $lastname));
            if ($name_alpha_spaces) {
                $resp = $this->keeperDAO->updatelastname($keeperCode,$lastname);
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
	
	public function srv_editTypeCare($keeperCode,$typeCare)
	{
		try{
			
            if ($typeCare != Size::BIG && $typeCare != Size::MEDIUM && $typeCare != Size::SMALL) {
                $resp = $this->keeperDAO->updateTypeCare($keeperCode,$typeCare);
			}else{
				$resp = "Typecare doesn't match!";
			}
			if($resp != 1)
			{
				$resp = "Not modified type care"; 
			}
		}catch(Exception $ex)
		{
			$resp = $ex->getMessage();
		}
		return $resp;
	}
	
	public function srv_editTypePet($keeperCode,$typePet)
	{
		try{
			
            if ($typePet != "cat" && $typePet != "dog") {
                $resp = $this->keeperDAO->updateTypePet($keeperCode,$typePet);
			}else{
				$resp = "Typepet doesn't match!";
			}
			if($resp != 1)
			{
				$resp = "Not modified type pet"; 
			}
		}catch(Exception $ex)
		{
			$resp = $ex->getMessage();
		}
		return $resp;
	}
	
	public function srv_editScore($keeperCode,$score)
	{
		try{
			
            if (ctype_digit($score) && $score >= 1 && $score <= 5) {
                $resp = $this->keeperDAO->updateScore($keeperCode,$score);
			}else{
				$resp = "Score doesn't match has to be between 1 - 5!";
			}
			if($resp != 1)
			{
				$resp = "Not allowed score"; 
			}
		}catch(Exception $ex)
		{
			$resp = $ex->getMessage();
		}
		return $resp;
	}
	
	public function srv_editPrice($keeperCode,$price)
	{
		try{
			
            if (ctype_digit($price)) {
                $resp = $this->keeperDAO->updatePrice($keeperCode,$price);
			}else{
				$resp = "Price must be a number";
			}
			if($resp != 1)
			{
				$resp = "Not allowed price"; 
			}
		}catch(Exception $ex)
		{
			$resp = $ex->getMessage();
		}
		return $resp;
	}
	
	public function listKeeperFiltered($code)
	{
        try {
        if (strpos($code, "KEP") !== false || 
            filter_var($code, FILTER_VALIDATE_EMAIL) || 
            (preg_match("/^\d{8}$/",$code) == 1))
			{
				$keepList = $this->keeperDAO->getFilteredKeepsAdm($code);
			}else {
				$keepList = "Not matching results.Remember to use BOOK,OWN,PET or KEP";
				}
        }catch(Exception $ex)
		{
			$keepList = $ex->getMessage();
		}
		return $keepList;
	}
}
