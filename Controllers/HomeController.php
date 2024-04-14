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
        } else {
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
}
