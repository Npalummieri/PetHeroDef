<?php

namespace Controllers;

use Models\User as User;
use Models\Owner as Owner;
use Models\Keeper as Keeper;
use DAO\ownerDAO as OwnerDAO;
use DAO\keeperDAO as KeeperDAO;
use \Exception as Exception;
use Controllers\HomeController as HomeController;
use Utils\Session as Session;
use Services\UserService as UserService;

class AuthController{

    private $ownerDAO;
    private $homeController;
    private $userService;
    private $keeperDAO;
    private $availabilityDAO;

    
    public function __construct()
    {
        $this->ownerDAO = new OwnerDAO();
        $this->homeController = new HomeController();
        $this->userService = new UserService($this->ownerDAO,$this->keeperDAO);
        $this->keeperDAO = new KeeperDAO();
    }


    

    public function Login($userField,$password)
    {
        try{
            if(filter_var($userField,FILTER_VALIDATE_EMAIL))
            {
                $user = $this->userService->searchEmailLogin($userField);
            }else
            {
                $user = $this->userService->searchUsernameLogin($userField);
            }
            
            if($user == null)
            {
                $msge = "User not found,try again!";
                //throw New Exception("Fatal error,not user found");
                $this->homeController->showLoginView($msge);
            }else
            {
                if($this->userService->checkPassword(get_class($user),$user->getEmail(),$password))
            {
                
                $msge = "Login Successfully";
                Session::CreateSession($user);
                $userLogged = Session::GetLoggedUser();
                if($userLogged->getStatus == "inactive")
                {
                    if($userLogged instanceof Owner)
                {
                    $this->userService->updateStatusUser($userLogged->getOwnerCode());
                }else{
                    $this->userService->updateStatusUser($userLogged->getKeeperCode());
                }
                }
                
                $this->homeController->Index($msge);
            }else
            {
                $msge = "Wrong password!";
                $this->homeController->showLoginView($msge);
            }
            }

            
        }catch(Exception $ex)
        {
            echo $ex->getMessage();
        }
    }

    
}

?>