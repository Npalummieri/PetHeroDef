<?php

namespace Controllers;

use Services\KeeperService as KeeperService;
use Services\OwnerService as OwnerService;
use Services\UserService as UserService;
use Utils\Session as Session;

class HomeController{

    private $userService;
    private $ownerService;
    private $keeperService;
    private $ownerController;

    public function __construct()
    {
        $this->ownerService = new OwnerService();
        $this->keeperService = new KeeperService();
        $this->userService = new UserService();
        //$this->ownerController = new OwnerController();
    }

    public function Index($msgResult = " ")
    {

        //Es exactamente lo mismo que Owner/showKeeperListPag pero ya seteamos en 1 para que al requerir el doc keeperListPag ya venga la 1pag
        //Quiza usar un header sea mejor? Quizá
        //Sin ceil la cuenta resulta en 1.5 y nunca redondea para dar paso a la sig pagina
        $totalPages = ceil(count($allKeepers = $this->userService->getKeepersInfoAvai()) / 6);

        $allKeepers = $this->userService->srv_getKeepersInfoAvaiPag(1, 6);
        require_once(VIEWS_PATH . "index.php");
    }

    //Ahora
    public function showKeeperListPag($pageNumber)
    {

        //Sin ceil la cuenta resulta en 1.5 y nunca redondea para dar paso a la sig pagina
        $totalPages = ceil(count($allKeepers = $this->userService->getKeepersInfoAvai()) / 6);

        $allKeepers = $this->userService->srv_getKeepersInfoAvaiPag($pageNumber, 6);


        require_once(VIEWS_PATH . "keeperListPag.php");
    }
    public function showOwnerRegisterView($msgResult = " ")
    {
        require_once(VIEWS_PATH."registerOwner.php");
    }

    public function showKeeperRegisterView($msgResult = " ")
    {
        require_once(VIEWS_PATH."registerKeeper.php");
    }

    public function showLoginView($message = " ")
    {
        require_once(VIEWS_PATH."login.php");
    }

    public function showChooseRegister()
    {
        require_once(VIEWS_PATH."chooseRegister.php");
    }

    public function showKeeperListView($keeperListParam)
    {
        echo "keeperListParam :";
        var_dump($keeperListParam);
        $newArray = array();
        array_push($newArray,$keeperListParam);
        // echo "keeperListParam :";
        // var_dump($keeperListParam);
        $allKeepers = $newArray;
        require_once(VIEWS_PATH."keeperListPag.php");
    }

    public function Logout()
    {
        Session::DeleteSession();
        header("Location: ../index.php");
        exit();
    }

    public function doBio($bio,$userCode)
    {
        var_dump($bio);
        var_dump($userCode);
        echo "POST";
        var_dump($_POST);
        if(Session::IsLogged())
        {
            $result = $this->userService->srv_updateBio($bio,$userCode);
        }else{
            header("location: ".FRONT_ROOT."Home/Index");
        }
       
    }

    
}

?>