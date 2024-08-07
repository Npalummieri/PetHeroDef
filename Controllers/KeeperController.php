<?php

namespace Controllers;


use Models\Keeper as Keeper;
use Services\KeeperService as KeeperService;
use Services\UserService as UserService;
use Utils\Session as Session;
use Services\ReviewService as ReviewService;


class KeeperController
{

    private $keeperService;
    private $userService;
    private $reviewService;

    public function __construct()
    {
        $this->keeperService = new KeeperService();
        $this->userService = new UserService();
        $this->reviewService = new ReviewService();
    }


    public function registerKeeper($email, $username, $password, $name, $lastname, $dni, $pfp, $typePet, $typeCare, $initDate, $endDate, $price, $visitPerDay)
    {
        
        $pfpInfo = array();
        $pfpInfo = $_FILES;
        $userInfo = $this->userService->validateRegisterUser("keeper", $email, $username, $password, $name, $lastname, $dni, $pfpInfo);

        if (is_string($userInfo)) {
            Session::SetBadMessage($userInfo);
            header("location: " . FRONT_ROOT . "Home/showKeeperRegisterView");
        } else if (is_array($userInfo)) {
            $user = $this->keeperService->validateKeeperFields($userInfo, $typePet, $typeCare, $initDate, $endDate, $price, $visitPerDay);
            if (!($user instanceof Keeper)) {
                Session::SetBadMessage($user . "Error en el registro. Intente nuevamente");
                header("location: " . FRONT_ROOT . "Home/Index");
            }

            if ($userInfo["user"] !== null && $user instanceof Keeper) {
                Session::SetOkMessage("Registrado");
                header("location: " . FRONT_ROOT . "Home/Index");
            } else {
                Session::SetBadMessage($user . "Error en el registro. Intente nuevamente");
                header("location: " . FRONT_ROOT . "Home/Index");
            }
        }
    }




    public function getFilteredKeepers($initDate, $endDate, $size, $typePet, $visitPerDay, $pageNumber = '1')
    {
        $filteredKeepers = $this->userService->srv_GetFilteredKeepers($initDate, $endDate, $size, $typePet, $visitPerDay, $pageNumber, 6);
        if(!is_string($filteredKeepers))
        {
            $totalPages = ceil(count($filteredKeepers) / 6);

            $allKeepers = $filteredKeepers;
            require_once(VIEWS_PATH . "keeperListPag.php");
        }else{
            Session::SetBadMessage($filteredKeepers);
            header("location: " . FRONT_ROOT . "Home/Index");
            
        }
        
        
    }


    public function showProfileKeeper($keeperCode = "")
    {

        $loggedKeeper = null;
        $loggedOwner = null;

        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Keeper") {
                $loggedKeeper = Session::GetLoggedUser();
                $keeperCode = $loggedKeeper->getKeeperCode();
            } else if (Session::GetTypeLogged() == "Models\Owner") {
                $loggedOwner = Session::GetLoggedUser();
            }
        }
        $keeper = $this->keeperService->srv_getKeeperByCode($keeperCode);
        $reviews = $this->reviewService->srv_GetReviews($keeperCode);
        $infoKeeper = $this->keeperService->srv_getKeeperByCode($keeperCode);
        require_once(VIEWS_PATH . "profileKeeper.php");
    }


    public function showUpdateKeeper()
    {
        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Keeper") {

                $keeperLogged = Session::GetLoggedUser();
                require_once(VIEWS_PATH . "editProfileKeep.php");
            }
        } else {
            header("location: " . FRONT_ROOT . "Home/Index");
        }
    }


    public function updateKeeper($email = " ", $pfp = " ", $bio = " ",$price = " ", $visitPerDay = " ")
    {

        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Keeper") {

                $keeperLogged = Session::GetLoggedUser();
                $pfpInfo = $_FILES;
                $result = $this->keeperService->srv_updateKeeper($keeperLogged, $email, $pfpInfo, $bio, $price, $visitPerDay);

                if ($result == 1) {
                    $infoKeeper = $this->keeperService->srv_getKeeperByCode($keeperLogged->getKeeperCode());
                    $infoKeeper = $this->keeperService->srv_getKeeperByCode($keeperLogged->getkeeperCode());
                    //Delet and re-create to get the updated info!
                    Session::DeleteSession();
                    Session::CreateSession($infoKeeper);
                    require_once(VIEWS_PATH . "myProfileKeeper.php");
                }
            }
        } else {
            header("location: '../index.php'");
        }
    }

    public function getIntervalDates($keeperCode)
    {
        $intervalReturned = $this->keeperService->srv_getIntervalDates($keeperCode);

        $encodedInterval = json_encode($intervalReturned);

        echo $encodedInterval;
    }

    public function updateAvailability($initDate, $endDate)
    {
        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Keeper") {
                $loggedKeeper = Session::GetLoggedUser();
                $keeperCode = $loggedKeeper->getKeeperCode();
                $result = $this->keeperService->srv_updateAvailability($keeperCode, $initDate, $endDate);
            }else{
                Session::DeleteSession();
                header("location: ".FRONT_ROOT."Home/showLoginView");
            }
        }else{
            header("location: ".FRONT_ROOT."Home/showLoginView");
        }
        $resEncode = json_encode($result);
        echo $resEncode;
    }

    public function getVisitPerDay($keeperCode)
    {
        $keeper = $this->keeperService->srv_getKeeperByCode($keeperCode);

        $visitPerDay = $keeper->getVisitPerDay();
        $encodedVisit = json_encode($visitPerDay);
        echo ($encodedVisit);
    }

    public function getAvailability($keeperCode)
    {
        $datesInterval = $this->keeperService->srv_getDates($keeperCode);

        $datesEncoded = json_encode($datesInterval);

        echo $datesEncoded;
    }
	
		//|||||||||||||||||||||||| Keeper edits
	
		public function showListKeepers()
	{
		if(Session::IsLogged())
		{
			$checkAdmin = Session::GetLoggedUser();
			
			if($checkAdmin != null)
			{
				if((is_a(Session::GetLoggedUser(),"Models\Admin")))
				{
					$listKeeps = $this->keeperService->srv_getAllKeepers();
					require_once(VIEWS_PATH."listKeepers.php");
				}else{
					Session::DeleteSession();
					header("location: ".FRONT_ROOT."Home/showLoginView");
				}
			}else{
				Session::DeleteSession();
				header("location: ".FRONT_ROOT."Home/showLoginView");
			}
			
			}else{
				header("location: ".FRONT_ROOT."Home/showLoginView");
			}
	}
	

	
	public function showEditKeeper($keeperCode)
	{
		if((is_a(Session::GetLoggedUser(),"Models\Admin")))
		{
			$keeper = $this->keeperService->srv_getKeeperByCode($keeperCode);
			require_once(VIEWS_PATH."adminEditKeep.php");
		}
	}
	
	public function adminEditKeeper($keeperCode, $email = "", $username = "", $status = "", $name = "", $lastname = "",$typeCare = "",$typePet = "",$score = "",$price = "")
	{

        
		$edits = array(
			"email" => $email,
			"username" => $username,
			"status" => $status,
			"name" => $name,
			"lastname" => $lastname,
			"typeCare" => $typeCare,
			"typePet" => $typePet,
			"score" => $score,
			"price" => $price
			
		);
        $resultOkFinal = null;
        $resultFinal = null;

		foreach ($edits as $field => $value) {
			if (!empty($value)) {
				$methodName = "srv_edit" . ucfirst($field);
				$result = $this->keeperService->$methodName($keeperCode, $value);
				if($result == 1){
					$resultOkFinal .= " || ".ucfirst($field)." modificado con exito.";
					Session::SetOkMessage($resultOkFinal);
				}else if($result == 0)
                {
                    Session::SetOkMessage("");
                }else{
                    $resultFinal .= $result." - ".ucfirst($field)." no se pudo modificar <br>";
                    Session::SetBadMessage($resultFinal);
                }
			}
		}
		header("location: " . FRONT_ROOT . "Keeper/showListKeepers");
	}
	
		public function listKeepersFiltered($code ="")
	{
		
		if($code == "")
		{
			header("location: " . FRONT_ROOT . "Keeper/showListKeepers");
		}
		$listKeeps = $this->keeperService->listKeeperFiltered($code);
		if(is_array($listKeeps)){
			require_once(VIEWS_PATH."listKeepers.php");
		}else if($code != ""){
			Session::SetBadMessage($listKeeps);
			header("location: " . FRONT_ROOT . "Keeper/showListKeepers");
		}			
	}
}
