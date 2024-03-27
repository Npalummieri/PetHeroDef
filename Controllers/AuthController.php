<?php

namespace Controllers;


use Models\Owner as Owner;
use DAO\ownerDAO as OwnerDAO;
use DAO\keeperDAO as KeeperDAO;
use Controllers\HomeController as HomeController;
use Utils\Session as Session;
use Services\UserService as UserService;

class AuthController{

    private $ownerDAO;
    private $userService;
    private $keeperDAO;


    
    public function __construct()
    {
        $this->ownerDAO = new OwnerDAO();
        $this->userService = new UserService($this->ownerDAO,$this->keeperDAO);
        $this->keeperDAO = new KeeperDAO();
    }


    

    public function Login($userField,$password)
    {

            if(filter_var($userField,FILTER_VALIDATE_EMAIL))
            {
                $user = $this->userService->searchEmailLogin($userField);
            }else
            {
                $user = $this->userService->searchUsernameLogin($userField);
            }
            
            if($user == null)
            {
                Session::SetBadMessage("User not found");
                //throw New Exception("Fatal error,not user found");
                header("location: ".FRONT_ROOT."Home/showLoginView");
            }else
            {
                if($this->userService->checkPassword(get_class($user),$user->getEmail(),$password))
            {
                
                Session::SetOkMessage("Login Successfully!");
                Session::CreateSession($user);
                $userLogged = Session::GetLoggedUser();
                if($userLogged->getStatus() == "inactive")
                {
                    if($userLogged instanceof Owner)
                {
                    $this->userService->updateStatusUser($userLogged->getOwnerCode());
                }else{
                    $this->userService->updateStatusUser($userLogged->getKeeperCode());
                }
                }
                
                header("location: ".FRONT_ROOT."Home/Index");
            }else
            {
                Session::SetBadMessage("Wrong password!");
                header("location: ".FRONT_ROOT."Home/showLoginView");
            }
            } 
        }
    }
    

    

?>