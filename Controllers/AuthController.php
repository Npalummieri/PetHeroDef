<?php

namespace Controllers;

use DAO\ownerDAO as OwnerDAO;
use DAO\keeperDAO as KeeperDAO;
use Utils\Session as Session;
use Services\UserService as UserService;
use Models\Status as Status;


class AuthController
{

    private $ownerDAO;
    private $userService;
    private $keeperDAO;

    public function __construct()
    {
        $this->ownerDAO = new OwnerDAO();
        $this->keeperDAO = new KeeperDAO();
        $this->userService = new UserService($this->ownerDAO, $this->keeperDAO);
    }

    public function Login($userField, $password)
    {
        $user = $this->userService->validateLogin($userField);
        if (is_string($user)) {
            Session::SetBadMessage($user);
            header("location: " . FRONT_ROOT . "Home/showLoginView");
        } else if ($user == null) {
            Session::SetBadMessage("Not user found");
            header("location: " . FRONT_ROOT . "Home/showLoginView");
        } else {
            
            if ($this->userService->checkPassword(get_class($user), $user->getEmail(), $password)) {

                
                Session::CreateSession($user);
                $userLogged = Session::GetLoggedUser();
                if (is_a($userLogged,"Models\Admin")) {
                    Session::SetOkMessage("Admin logueado correctamente");
                    header("location: " . FRONT_ROOT . "Home/showDashboard");
                    var_dump(Session::GetLoggedUser());
                } else {
                    //"active" the status for the user
                    Session::SetOkMessage("Logueado correctamente");
                    if ($userLogged->getStatus() === Status::INACTIVE) {
                        if (Session::GetTypeLogged() == "Models\Owner") {
                            $this->userService->srv_updateStatusUser($userLogged->getOwnerCode(), Status::ACTIVE);
                        } else {
                            $this->userService->srv_updateStatusUser($userLogged->getKeeperCode(), Status::ACTIVE);
                        }
                    }
                    header("location: " . FRONT_ROOT . "Home/Index");
                }
            } else {
                Session::SetBadMessage("Contraseña incorrecta");
                header("location: " . FRONT_ROOT . "Home/showLoginView");
                // var_dump($this->userService->checkPassword(get_class($user), $user->getEmail(), $password));
            }
        }
    }
    

    public function recoverPasswordView()
    {
        require_once(VIEWS_PATH . "recoverPassword.php");
    }

    public function recoverPassword($email, $dni)
    {
        $resp = $this->userService->srv_resetPassword($email, $dni);
        if ($resp != 1) {
            Session::SetBadMessage("Credenciales no validas");
        } else {
            Session::SetOkMessage("Contraseña reestablecida,revise su email");
        }
        header("location: " . FRONT_ROOT . "Home/showLoginView");
    }
}
