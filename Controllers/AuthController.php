<?php

namespace Controllers;


use Models\Owner as Owner;
use DAO\ownerDAO as OwnerDAO;
use DAO\keeperDAO as KeeperDAO;
use Utils\Session as Session;
use Services\UserService as UserService;

class AuthController{

    private $ownerDAO;
    private $userService;
    private $keeperDAO;


    
    public function __construct()
    {
        $this->ownerDAO = new OwnerDAO();
        $this->keeperDAO = new KeeperDAO();
        $this->userService = new UserService($this->ownerDAO,$this->keeperDAO);
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
                header("location: ".FRONT_ROOT."Home/showLoginView");
            }else
            {
                if($this->userService->checkPassword(get_class($user),$user->getEmail(),$password))
            {
                
                Session::SetOkMessage("Login successfully");
                Session::CreateSession($user);
                $userLogged = Session::GetLoggedUser();
                //"active" the status for the user

                if($userLogged->getStatus() === "inactive")
                {
                    if(Session::GetTypeLogged() == "Models\Owner")
                {
                    $this->userService->srv_updateStatusUser($userLogged->getOwnerCode(),"active");
                }else{
                    $this->userService->srv_updateStatusUser($userLogged->getKeeperCode(),"active");
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

        public function recoverPasswordView()
        {
            require_once(VIEWS_PATH."recoverPassword.php");
        }

        public function recoverPassword($email,$dni)
        {
            $resp = $this->userService->srv_resetPassword($email,$dni);
            if($resp != 1)
            {
                Session::SetBadMessage("Not valid credentials!");
                
            }else{
                Session::SetOkMessage("Password recovered,check your email!");
                
            }
            header("location: ".FRONT_ROOT."Home/showLoginView");
        }
    }

    
    

    

?>