<?php

namespace Controllers;

use \Exception as Exception;
use Models\Owner as Owner;
use DAO\OwnerDAO as OwnerDAO;
use Services\PetService as PetService;
use Utils\Session as Session;
use Services\OwnerService as OwnerService;
use Services\UserService as UserService;
use Controllers\HomeController as HomeController;

class OwnerController{

    private $ownerDAO;
    private $petService;
    private $ownerService;
    private $userService;
    private $homeController;

    public function __construct()
    {
        $this->ownerDAO = new OwnerDAO();
        $this->petService = new PetService();
        $this->ownerService = new OwnerService();
        $this->userService = new UserService();
        $this->homeController = new HomeController;
    }

    public function registerOwner($email, $username, $password, $name, $lastname, $dni, $pfp)
    {
        $pfpInfo = array();
        $pfpInfo = $_FILES;
        $typeUser = "owner";
        $userInfo = $this->userService->validateRegisterUser($typeUser, $email, $username, $password, $name, $lastname, $dni, $pfpInfo);
        $userOwn = new Owner();
        $userOwn->fromUserToOwner($userInfo["user"]);


        $result = $this->ownerService->srv_add($userOwn, $userInfo);
        if ($result == 1) {
            $msgResult = "Successfully registered!";
            $this->homeController->Index($msgResult);
        } else {
            $this->homeController->showOwnerRegisterView("Error at the register");
        }
    }

    public function showAddPet()
    {
        if(Session::IsLogged() && Session::GetTypeLogged() == 'Models\Owner')
        {

                $user = Session::GetLoggedUser();
                $ownerCode = $user->getOwnerCode();
                require_once(VIEWS_PATH."addPet.php");
            
        }else{
            Session::DeleteSession();
            require_once(VIEWS_PATH."index.php");
        }
    }

    public function showMyPets($msge = " ")
    {
        if(Session::IsLogged())
        {
            
            $logged = $_SESSION["loggedUser"];
            
            $myPets = $this->petService->getAllByOwner($logged->getOwnerCode());
            // $dir = (__DIR__);
            // echo $dir;
            require_once(VIEWS_PATH."showMyPets.php");
        }
        
    }

    public function showKeepersList()
    {
        try
        {
            $allKeepers = $this->userService->getKeepersInfoAvai();
        }catch(Exception $ex)
        {
            echo $ex->getMessage();
        }
        require_once(VIEWS_PATH."keeperList.php");
    }



    public function showMyProfile()
    {
        if (Session::IsLogged()) {
            $ownerLogged = Session::GetLoggedUser();
            //echo "<br> ownerlogged :". var_dump($ownerLogged);
            $infoOwner = $this->ownerService->getByCode($ownerLogged->getOwnerCode());
            //var_dump($infoOwner);
            require_once(VIEWS_PATH . "myProfileOwner.php");
        }else
        {
            require_once(VIEWS_PATH . "index.php");
        }
        
    }

    public function editProfile(){

        if(Session::IsLogged()){
            if(Session::GetTypeLogged() == "Models\Owner"){
                $ownerLogged = Session::GetLoggedUser();
                $infoOwner = $this->ownerService->getByCode($ownerLogged->getOwnerCode());
                require_once(VIEWS_PATH."editProfileOwn.php");
            }
        }
    }

    public function updateOwner($pfp = " ", $email = " ", $bio = " ")
    {
        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Owner") {
                $ownerLogged = Session::GetLoggedUser();
                echo "POST CONTROLLER";
                var_dump($_POST);
                $pfpInfo = $_FILES;
                $result = $this->ownerService->srv_updateOwner($ownerLogged->getOwnerCode(),$pfpInfo, $email, $bio);
                if($result == 1)
                {
                    //Info actualizada
                    $infoOwner = $this->ownerService->getByCode($ownerLogged->getOwnerCode());
                    Session::DeleteSession();
                    Session::CreateSession($infoOwner);
                    require_once(VIEWS_PATH."myProfileOwner.php");
                }
            }
        }else{
            header("location: '../index.php'");
        }
    }

}

?>