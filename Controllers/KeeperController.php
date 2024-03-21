<?php

namespace Controllers;

use \Exception as Exception;
use Models\Keeper as Keeper;
use Services\KeeperService as KeeperService;
use Services\BookingService as BookingService;
use Controllers\HomeController as HomeController;
use Services\UserService as UserService;
use Utils\Session as Session;
use Services\ReviewService as ReviewService;


class KeeperController{

    private $keeperService ;
    private $homeController;
    private $bookingService;
    private $userService;
    private $reviewService;

    public function __construct()
    {
        $this->keeperService = new KeeperService();
        $this->homeController = new HomeController();
        $this->bookingService = new BookingService();
        $this->userService = new UserService();
        $this->reviewService = new ReviewService();
    }
    //19012024 Deberia mandarlo a keeperController
    public function registerKeeper($email,$username,$password,$name,$lastname,$dni,$pfp,$typePet,$typeCare,$initDate,$endDate,$price,$visitPerDay)
    {
        try{

                echo "POST :";
                var_dump($_POST);
                echo "<br>";
                echo "FILES :";
                var_dump($_FILES);
                echo "<br>";
                $typeUser = "keeper";
                $pfpInfo = array();
                $pfpInfo = $_FILES;
                $userInfo = $this->userService->validateRegisterUser($typeUser,$email,$username,$password,$name,$lastname,$dni,$pfpInfo);
                echo "USERINFO ";
                var_dump($userInfo);
                echo "<br>";
                echo "USER KEEPER ";
                var_dump($userInfo["user"]);
                echo "<br>";
                
                //Llega el keeperValidado
                $user = $this->keeperService->validateKeeperFields($userInfo,$typePet,$typeCare,$initDate,$endDate,$price,$visitPerDay);
                echo "USER KEEPER despues de validateKeeperFields ";
                var_dump($userInfo["user"]);
                echo "<br>";
                //var_dump(get_class($user));

                //Teoricamente llegan validados del validateRegisterUser+validateKeeperFields
                if($userInfo["user"] !== null)
                {
                    $msgResult = "Successfully registered!";
                    $this->homeController->Index($msgResult);
                    
                }else
                {
                    throw new Exception("Something wrong at validateUser");
                }  
            }catch(Exception $ex)
            {
                $msgeResult = $ex->getMessage();
                echo $msgeResult;
            }
    }

    


    public function getFilteredKeepers($initDate,$endDate,$size,$typePet,$visitPerDay,$pageNumber = '1')
    {
        //var_dump($_POST);

        $totalPages = ceil(count($this->userService->srv_GetFilteredKeepers($initDate,$endDate,$size,$typePet,$visitPerDay,$pageNumber,6)) / 6);

        $allKeepers = $this->userService->srv_GetFilteredKeepers($initDate,$endDate,$size,$typePet,$visitPerDay,$pageNumber,6);
        
        require_once(VIEWS_PATH."keeperListPag.php");
    }


    public function showProfileKeeper($keeperCode = "")
    {

        $logged = null;
        $loggedKeeper = null;
        $loggedOwner = null;
        echo "KEEPER CODE".$keeperCode;
                if(Session::IsLogged())
                {
                    if(Session::GetTypeLogged() == "Models\Keeper")
                    {
                        $loggedKeeper = Session::GetLoggedUser();
                        $keeperCode = $loggedKeeper->getKeeperCode();
                    }else if (Session::GetTypeLogged() == "Models\Owner")
                    {
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
                require_once(VIEWS_PATH."editProfileKeep.php");
            }
        } else {
            header("location: '../index.php'");
        }
    }

    //¿Deberia poder cambiar typePet,typeCare?
    public function updateKeeper($email = " ",$pfp = " ",$bio =" ",$price = " ",$visitPerDay = " ")
    {
        var_dump($_POST);
        if(Session::IsLogged())
        {
            if(Session::GetTypeLogged() == "Models\Keeper")
            {

                $keeperLogged = Session::GetLoggedUser();
                $pfpInfo = $_FILES;
                $result = $this->keeperService->srv_updateKeeper($keeperLogged,$email,$pfpInfo,$bio,$price,$visitPerDay);
                echo "SOY RESULT";
                var_dump($result);
                if($result == 1)
                {
                    $infoKeeper = $this->keeperService->srv_getKeeperByCode($keeperLogged->getKeeperCode());
                    $infoKeeper = $this->keeperService->srv_getKeeperByCode($keeperLogged->getkeeperCode());
                    Session::DeleteSession();
                    Session::CreateSession($infoKeeper);
                    require_once(VIEWS_PATH."myProfileKeeper.php");
                }
            }
        }else{
            header("location: '../index.php'");
        }
    }

    public function getIntervalDates($keeperCode)
    {
        $intervalReturned = $this->keeperService->srv_getIntervalDates($keeperCode);

        $encodedInterval = json_encode($intervalReturned);

        echo $encodedInterval;
    }

    public function updateAvailability($initDate,$endDate)
    {
        if(Session::IsLogged())
        {
            if(Session::GetTypeLogged() == "Models\Keeper")
            {
                $loggedKeeper = Session::GetLoggedUser();
                $keeperCode = $loggedKeeper->getKeeperCode();
                $this->keeperService->srv_updateAvailability($keeperCode,$initDate,$endDate);
            }
        }
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


    


}

?>