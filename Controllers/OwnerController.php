<?php

namespace Controllers;


use Models\Owner as Owner;
use Services\PetService as PetService;
use Utils\Session as Session;
use Services\OwnerService as OwnerService;
use Services\UserService as UserService;
use Utils\Dates as Dates;

class OwnerController
{


    private $petService;
    private $ownerService;
    private $userService;


    public function __construct()
    {

        $this->petService = new PetService();
        $this->ownerService = new OwnerService();
        $this->userService = new UserService();
    }

    public function registerOwner($email, $username, $password, $name, $lastname, $dni, $pfp)
    {
        $pfpInfo = array();
        $pfpInfo = $_FILES;
        $typeUser = "owner";
        $userInfo = $this->userService->validateRegisterUser($typeUser, $email, $username, $password, $name, $lastname, $dni, $pfpInfo);
        if (is_array($userInfo)) {
            $userOwn = new Owner();
            $userOwn->fromUserToOwner($userInfo["user"]);

            $result = $this->ownerService->srv_add($userOwn, $userInfo);
            if ($result == 1) {
                Session::SetOkMessage("Registrado con exito");
                header("location: " . FRONT_ROOT . "Home/Index");
            } else {
                Session::SetBadMessage($result);
                header("location: " . FRONT_ROOT . "Home/showOwnerRegisterView");
            }
        } else if (is_string($userInfo)) {
            Session::SetBadMessage($userInfo);
            header("location: " . FRONT_ROOT . "Home/showOwnerRegisterView");
        }
    }

    public function showAddPet()
    {
        if (Session::IsLogged() && Session::GetTypeLogged() == 'Models\Owner') {

            $user = Session::GetLoggedUser();
            $ownerCode = $user->getOwnerCode();
            require_once(VIEWS_PATH . "addPet.php");
        } else {
            Session::DeleteSession();
            require_once(VIEWS_PATH . "index.php");
        }
    }

    public function showMyPets($msge = " ")
    {
        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Owner") {
                $logged = $_SESSION["loggedUser"];
                $myPets = $this->petService->getAllByOwner($logged->getOwnerCode());
                require_once(VIEWS_PATH . "showMyPets.php");
            }else{
                Session::DeleteSession();
                header("location: ".FRONT_ROOT."Home/showLoginView");
            }
        } else {
            header("location: ".FRONT_ROOT."Home/showLoginView");
        }
    }


    public function showMyProfile()
    {
        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Owner") {
                $ownerLogged = Session::GetLoggedUser();

                $infoOwner = $this->ownerService->getByCode($ownerLogged->getOwnerCode());

                if($infoOwner->getSuspensionDate() != null)
                {
                    $remsuspense = Dates::remainingSuspense($ownerLogged->getOwnerCode());
                }
                require_once(VIEWS_PATH . "myProfileOwner.php");
            } else {
                Session::DeleteSession();
                header("location: " . FRONT_ROOT . "Home/showLoginView");
            }
        } else {
            header("location: " . FRONT_ROOT . "Home/Index");
        }
    }

    public function editProfile()
    {

        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Owner") {
                $ownerLogged = Session::GetLoggedUser();
                $infoOwner = $this->ownerService->getByCode($ownerLogged->getOwnerCode());
                require_once(VIEWS_PATH . "editProfileOwn.php");
            }
        }else{
            header("location: ".FRONT_ROOT."Home/showLoginView");
        }
    }

    public function updateOwner($pfp = " ", $bio = " ")
    {
        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Owner") {
                $ownerLogged = Session::GetLoggedUser();
                $pfpInfo = $_FILES;
                $result = $this->ownerService->srv_updateOwner($ownerLogged->getOwnerCode(), $pfpInfo, $bio);
                if ($result == 1) {
                    //Updated info
                    $infoOwner = $this->ownerService->getByCode($ownerLogged->getOwnerCode());
                    Session::CreateSession($infoOwner);
                }
                header("location: " . FRONT_ROOT . "Owner/showMyProfile");
            }
        } else {
            header("location: " . FRONT_ROOT . "Home/Index");
        }
    }

    public function showProfileOwner($ownerCode)
    {
        $infoOwner = $this->ownerService->getByCode($ownerCode);
        require_once(VIEWS_PATH . "myProfileOwner.php");
    }
	
		// ||||||||||||||||||| Edit owners
		public function showListOwners()
	{
		if(Session::IsLogged())
		{
			$checkAdmin = Session::GetLoggedUser();
			
			if($checkAdmin != null)
			{
				if((is_a(Session::GetLoggedUser(),"Models\Admin")))
				{
					$listOwns = $this->ownerService->srv_getAllOwners();
					require_once(VIEWS_PATH."listOwners.php");
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
		
		
	public function showEditOwner($ownerCode)
	{
		if((is_a(Session::GetLoggedUser(),"Models\Admin")))
		{
			$owner = $this->ownerService->getByCode($ownerCode);
			require_once(VIEWS_PATH."adminEditOwn.php");
		}
	}
	

	public function adminEditOwner($ownerCode, $email = "", $username = "", $status = "", $name = "", $lastname = "", $suspensionDate = "")
	{
		$edits = array(
			"email" => $email,
			"username" => $username,
			"status" => $status,
			"name" => $name,
			"lastname" => $lastname,
			"suspensionDate" => $suspensionDate
		);
         $resultOkFinal = null;
         $resultFinal = null;
		foreach ($edits as $field => $value) {
			if (!empty($value)) {
				$methodName = "srv_edit" . ucfirst($field);
				$result = $this->ownerService->$methodName($ownerCode, $value);
				if($result == 1){
					$resultOkFinal .= " || ".ucfirst($field)." modificado con exito! <br>";
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

		header("location: " . FRONT_ROOT . "Owner/showListOwners");


	}	
	
		public function listOwnersFiltered($code ="")
	{
		
		if($code == "")
		{
			header("location: " . FRONT_ROOT . "Owner/showListOwners");
		}
		$listOwns = $this->ownerService->listOwnerFiltered($code);
		if(is_array($listOwns)){
			require_once(VIEWS_PATH."listOwners.php");
		}else if($code != ""){
			Session::SetBadMessage($listOwns);
			header("location: " . FRONT_ROOT . "Owner/showListOwners");
		}			
	}
	
	public function deleteOwner($ownerCode)
	{
		$result = $this->ownerService->srv_deleteOwner($ownerCode);
		if($result == 1)
		{
			Session::SetOkMessage("Permanently deleted owner");
			header("location: " . FRONT_ROOT . "Owner/showListOwners");
		}else{
			Session::SetBadMessage($result);
			header("location: " . FRONT_ROOT . "Owner/showListOwners");
		}
	}
}
