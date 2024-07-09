<?php

namespace Controllers;

use Services\PetService as PetService;
use Services\UserService as UserService;
use Utils\Session as Session;

class HomeController
{

    private $userService;
    private $petService;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->petService = new PetService();
    }

    public function Index($msgResult = " ")
    {
        $totalPages = ceil(count($allKeepers = $this->userService->getKeepersInfoAvai()) / 6);

        $allKeepers = $this->userService->srv_getKeepersInfoAvaiPag(1, 6);
        require_once(VIEWS_PATH . "index.php");
    }

    public function showKeeperListPag($pageNumber)
    {

        //Sin ceil la cuenta resulta en 1.5 y nunca redondea para dar paso a la sig pagina
        $totalPages = ceil(count($allKeepers = $this->userService->getKeepersInfoAvai()) / 6);

        $allKeepers = $this->userService->srv_getKeepersInfoAvaiPag($pageNumber, 6);


        require_once(VIEWS_PATH . "keeperListPag.php");
    }

    public function showOwnerRegisterView($msgResult = " ")
    {
        require_once(VIEWS_PATH . "registerOwner.php");
    }

    public function showKeeperRegisterView($msgResult = " ")
    {
        require_once(VIEWS_PATH . "registerKeeper.php");
    }

    public function showLoginView($message = " ")
    {
        require_once(VIEWS_PATH . "login.php");
    }

    public function showChooseRegister()
    {
        require_once(VIEWS_PATH . "chooseRegister.php");
    }

    public function showKeeperListView($keeperListParam)
    {
        $newArray = array();
        array_push($newArray, $keeperListParam);
        $allKeepers = $newArray;
        require_once(VIEWS_PATH . "keeperListPag.php");
    }

    public function Logout()
    {
        Session::DeleteSession();
        header("location: ".FRONT_ROOT."Home/Index");
        exit();
    }

    public function doBio($bio, $userCode)
    {
        if (Session::IsLogged()) {
            $result = $this->userService->srv_updateBio($bio, $userCode);
			Session::SetOkMessage("Bio actualizada");
        } else {
			Session::SetBadMessage("Sin autorización");
            header("location: " . FRONT_ROOT . "Home/Index");
        }
    }

    public function showHowWorks()
    {
        $images = $this->petService->srv_getAllPetsPfp();
        require_once(VIEWS_PATH . "howitworks.php");
    }

    public function getNotis()
    {
        if(Session::IsLogged())
        {
            //$user = Session::GetLoggedUser();

            if(Session::GetTypeLogged() == "Models\Owner")
            {
                $notis = $this->userService->srv_getNotis(Session::GetLoggedUser()->getOwnerCode());
                $notisencoded = json_encode($notis);
            }else if(Session::GetTypeLogged() == "Models\Keeper")
            {
                $notis = $this->userService->srv_getNotis(Session::GetLoggedUser()->getKeeperCode());
                $notisencoded = json_encode($notis);
            }else{
                Session::DeleteSession();
                header("location: ".FRONT_ROOT."Home/showLoginView");
            }
        }else{
            $notisencoded = null;
        }

        echo $notisencoded;
    }
    
    public function resetNotis()
    {
        if(Session::IsLogged())
        {
            //$user = Session::GetLoggedUser();

            if(Session::GetTypeLogged() == "Models\Owner")
            {
                $notis = $this->userService->srv_resetNotis(Session::GetLoggedUser()->getOwnerCode());
                
            }else if(Session::GetTypeLogged() == "Models\Keeper")
            {
                $notis = $this->userService->srv_resetNotis(Session::GetLoggedUser()->getKeeperCode());
                
            }else{
                Session::DeleteSession();
                header("location: ".FRONT_ROOT."Home/showLoginView");
            }
        }
    }
	
	public function showDashboard()
	{
		if(Session::IsLogged())
		{
			$checkAdmin = Session::GetLoggedUser();
			
			if($checkAdmin != null)
			{
				if(is_a($checkAdmin,"Models\Admin"))
				{
					require_once(VIEWS_PATH."dashboard.php");
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

    public function showAdminRegister()
    {
        $userLogged = Session::GetLoggedUser();
        if(is_a($userLogged,"Models\Admin"))
        {
            require_once(VIEWS_PATH."registerAdmin.php");
        }else{
            header("location: ".FRONT_ROOT."Home/showLoginView");
        }
    }

    public function addAdmin($email,$password,$dni)
    {
        $userLogged = Session::GetLoggedUser();
        if(is_a($userLogged,"Models\Admin"))
        {
            $this->userService->validateAdminRegister($email,$password,$dni);
            header("location: ".FRONT_ROOT."Home/showDashboard");
        }else{
            header("location: ".FRONT_ROOT."Home/showLoginView");
        }
    }
		
}
